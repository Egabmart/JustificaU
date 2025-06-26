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
        if (Auth::user()->role !== 'admin' && $justificacione->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }
        return view('justificaciones.edit', compact('justificacione'));
    }

    public function update(Request $request, Justificacion $justificacione)
    {
        if (Auth::user()->role !== 'admin' && $justificacione->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $validatedData = $request->validate([
            'clase' => 'required|string|max:255',
            'grupo' => 'required|string|max:50',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'reason' => 'required|string',
            'constancia' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'status' => 'required|in:Pendiente,Aprobada,Rechazada',
            'rejection_reason' => 'required_if:status,Rechazada|nullable|string|max:1000',
        ]);

        if(Auth::user()->role === 'admin'){
            $validatedData += $request->validate([
                'student_name' => 'required|string|max:255',
                'student_id'   => ['required', 'string', 'max:20', Rule::unique('justificacions')->ignore($justificacione->id)],
            ]);
        }
        
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