<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class JustificacionController extends Controller
{
    public function index()
    {
        $query = Justificacion::latest();
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }
        $justificaciones = $query->paginate(10);
        return view('justificaciones.index', compact('justificaciones'));
    }

    public function create()
    {
        return view('justificaciones.create');
    }

    public function store(Request $request)
    {
        // 1. Validamos los datos, incluyendo el nuevo campo 'anio_carrera'
        $validatedData = $request->validate([
            'anio_carrera' => 'required|string', // <-- ¡IMPORTANTE AÑADIR ESTO!
            'clase'        => 'required|string|max:255',
            'grupo'        => 'required|string|max:50',
            'fecha'        => 'required|date',
            'hora_inicio'  => 'required|date_format:H:i',
            'hora_fin'     => 'required|date_format:H:i|after:hora_inicio',
            'reason'       => 'required|string',
            'constancia'   => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        // 2. Obtenemos datos del usuario
        $user = Auth::user();
        $validatedData['user_id'] = $user->id;
        $validatedData['student_name'] = $user->name;
        $validatedData['student_id'] = $user->cif;

        // 3. Buscamos al profesor usando AHORA SÍ el año
        $carrera = $user->carrera;
        $anio = $validatedData['anio_carrera']; // <-- Obtenemos el año
        $clase = $validatedData['clase'];
        $grupo = $validatedData['grupo'];

        // Construimos la ruta de configuración completa
        $configPath = "docentes.{$carrera}.{$anio}.{$clase}.{$grupo}";
        
        // Usamos la función config() con la ruta correcta
        $profesorInfo = config($configPath);

        // Asignamos el nombre del profesor
        $validatedData['profesor'] = $profesorInfo['nombre'] ?? 'Profesor no asignado';

        // 4. Guardamos la constancia
        if ($request->hasFile('constancia')) {
            $validatedData['constancia_path'] = $request->file('constancia')->store('constancias', 'public');
        }

        // 5. Creamos la justificación
        Justificacion::create($validatedData);

        return redirect()->route('justificaciones.index')->with('success', '¡Justificación creada exitosamente!');
    }
    
    public function show(Justificacion $justificacione){}

    public function edit(Justificacion $justificacione)
    {
        // Validamos que el usuario pueda ver esta justificación (dueño o admin)
        if (Auth::user()->role !== 'admin' && $justificacione->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $anioDeLaClase = null;
        $carreraDelEstudiante = $justificacione->user->carrera;
        $claseBuscada = $justificacione->clase;
        $datosAcademicos = config('academic.' . $carreraDelEstudiante, []);

        // Lógica para encontrar a qué año pertenece la clase guardada
        foreach ($datosAcademicos as $anio => $clasesDelAnio) {
            if (is_array($clasesDelAnio) && array_key_exists($claseBuscada, $clasesDelAnio)) {
                $anioDeLaClase = $anio;
                break;
            }
        }

        // Pasamos la justificación y el año encontrado a la vista
        return view('justificaciones.edit', [
            'justificacione' => $justificacione,
            'anioSeleccionado' => $anioDeLaClase,
        ]);
    }

    public function update(Request $request, Justificacion $justificacione)
    {
        // Lógica para el Administrador
        if (Auth::user()->role === 'admin') {
            // El admin solo puede cambiar el estado y el motivo de rechazo.
            $validatedData = $request->validate([
                'status' => 'required|in:Pendiente,Aprobada,Rechazada',
                'rejection_reason' => 'required_if:status,Rechazada|nullable|string|max:1000',
            ]);

            if ($validatedData['status'] !== 'Rechazada') {
                $validatedData['rejection_reason'] = null;
            }

            $justificacione->update($validatedData);
            return redirect()->route('justificaciones.index')->with('success', '¡Estado de la justificación actualizado!');
        }
        
        // Lógica para el Estudiante
        else {
            // Verificamos que el estudiante sea el dueño de la justificación
            if ($justificacione->user_id !== Auth::id()) {
                abort(403, 'Acción no autorizada.');
            }

            // El estudiante puede editar todos los campos, excepto el estado.
            $validatedData = $request->validate([
                'anio_carrera' => 'required|string',
                'clase'        => 'required|string|max:255',
                'grupo'        => 'required|string|max:50',
                'fecha'        => 'required|date',
                'hora_inicio'  => 'required|date_format:H:i',
                'hora_fin'     => 'required|date_format:H:i|after:hora_inicio',
                'reason'       => 'required|string',
                'constancia'   => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048', // Opcional al editar
            ]);
            
            // Buscamos y asignamos al profesor correspondiente
            $carrera = Auth::user()->carrera;
            $anio = $validatedData['anio_carrera'];
            $clase = $validatedData['clase'];
            $grupo = $validatedData['grupo'];
            $profesorInfo = config("docentes.{$carrera}.{$anio}.{$clase}.{$grupo}");
            $validatedData['profesor'] = $profesorInfo['nombre'] ?? 'Profesor no asignado';

            // Actualizamos la justificación con los datos del estudiante
            $justificacione->update($validatedData);

            // Si se sube una nueva constancia, la procesamos
            if ($request->hasFile('constancia')) {
                if ($justificacione->constancia_path) {
                    Storage::disk('public')->delete($justificacione->constancia_path);
                }
                $justificacione->constancia_path = $request->file('constancia')->store('constancias', 'public');
                $justificacione->save();
            }

            return redirect()->route('justificaciones.index')->with('success', '¡Justificación actualizada exitosamente!');
        }
    }

    public function destroy(Justificacion $justificacione)
    {
        if (Auth::user()->role !== 'admin' && $justificacione->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        if ($justificacione->constancia_path) {
            Storage::disk('public')->delete($justificacione->constancia_path);
        }
        $justificacione->delete();
        return redirect()->route('justificaciones.index')->with('success', '¡Justificación eliminada exitosamente!');
    }
}