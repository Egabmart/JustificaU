<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // <-- AÑADIR ESTA LÍNEA
use App\Mail\JustificacionAprobada;   // <-- AÑADIR ESTA LÍNEA
use App\Mail\JustificacionRechazada; // <-- CORRECTO

class JustificacionController extends Controller
{
    public function index()
    {
        // Obtenemos el usuario autenticado
        $user = Auth::user();

        // Verificamos el rol del usuario
        if ($user->role === 'admin') {
            // Si es administrador, obtiene todas las justificaciones.
            // Usamos with('user') para cargar la relación y evitar problemas de N+1.
            $justificaciones = Justificacion::with('user')->latest()->paginate(10);
        } else {
            // Si no es admin (es estudiante), solo obtiene sus propias justificaciones.
            $justificaciones = Justificacion::where('user_id', $user->id)
                                            ->latest()
                                            ->paginate(10);
        }

        // Enviamos las justificaciones (ya filtradas) a la vista.
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
            $validatedData = $request->validate([
                'status' => 'required|in:Pendiente,Aprobada,Rechazada',
                'rejection_reason' => 'required_if:status,Rechazada|nullable|string|max:1000',
            ]);

            // --- CORRECCIÓN AQUÍ ---
            // 1. Guardamos el estado ACTUAL de la justificación ANTES de actualizarla.
            $estadoAnterior = $justificacione->status;

            // 2. Actualizamos la justificación en la base de datos.
            $justificacione->update($validatedData);

            // 3. Comparamos el estado anterior con el nuevo para decidir si enviamos el correo.
            if ($estadoAnterior !== 'Aprobada' && $validatedData['status'] === 'Aprobada') {
                // Aseguramos que la relación 'user' esté cargada para evitar errores.
                $justificacione->load('user'); 
                Mail::to($justificacione->user->email)->send(new JustificacionAprobada($justificacione));
            }

            // Lógica de correo para RECHAZO
            if ($estadoAnterior === 'Pendiente' && $validatedData['status'] === 'Rechazada') {
                Mail::to($justificacione->user->email)->send(new JustificacionRechazada($justificacione));
            }

            return redirect()->route('justificaciones.index')->with('success', '¡Estado de la justificación actualizado!');
        }
        
        // Lógica para el Estudiante (esta parte no necesita cambios)
        else {
            if ($justificacione->user_id !== Auth::id()) {
                abort(403, 'Acción no autorizada.');
            }

            $validatedData = $request->validate([
                'anio_carrera' => 'required|string',
                'clase'        => 'required|string|max:255',
                'grupo'        => 'required|string|max:50',
                'fecha'        => 'required|date',
                'hora_inicio'  => 'required|date_format:H:i',
                'hora_fin'     => 'required|date_format:H:i|after:hora_inicio',
                'reason'       => 'required|string',
                'constancia'   => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            ]);
            
            $carrera = Auth::user()->carrera;
            $anio = $validatedData['anio_carrera'];
            $clase = $validatedData['clase'];
            $grupo = $validatedData['grupo'];
            $profesorInfo = config("docentes.{$carrera}.{$anio}.{$clase}.{$grupo}");
            $validatedData['profesor'] = $profesorInfo['nombre'] ?? 'Profesor no asignado';

            $justificacione->update($validatedData);

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