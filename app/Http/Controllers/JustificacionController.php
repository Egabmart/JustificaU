<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JustificacionController extends Controller
{
    /**
     * Muestra una lista de todas las justificaciones.
     */
    public function index()
    {
        $justificaciones = Justificacion::latest()->paginate(10);
        return view('justificaciones.index', [
            'justificaciones' => $justificaciones,
            'title' => 'Gestión de Justificaciones'
        ]);
    }

    /**
     * Muestra el formulario para crear una nueva justificación.
     */
    public function create()
    {
        return view('justificaciones.create', ['title' => 'Crear Justificación']);
    }

    /**
     * Guarda la nueva justificación en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos del formulario
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_id'   => 'required|string|max:20|unique:justificacions,student_id',
            'reason'       => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
        ]);

        // 2. Crear el registro en la base de datos
        Justificacion::create($request->all());

        // 3. Redirigir a la lista con un mensaje de éxito
        return redirect()->route('justificaciones.index')->with('success', '¡Justificación creada exitosamente!');
    }

    /**
     * Muestra un recurso específico (no usado en este CRUD).
     */
    public function show(Justificacion $justificacione)
    {
        //
    }

    /**
     * Muestra el formulario para editar una justificación existente.
     */
    public function edit(Justificacion $justificacione)
    {
        return view('justificaciones.edit', [
            'justificacione' => $justificacione,
            'title' => 'Editar Justificación'
        ]);
    }

    /**
     * Actualiza una justificación en la base de datos.
     */
    public function update(Request $request, Justificacion $justificacione)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_id'   => ['required', 'string', 'max:20', Rule::unique('justificacions')->ignore($justificacione->id)],
            'reason'       => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'status'       => 'required|in:Pendiente,Aprobada,Rechazada',
        ]);

        $justificacione->update($request->all());

        return redirect()->route('justificaciones.index')->with('success', '¡Justificación actualizada exitosamente!');
    }

    /**
     * Elimina una justificación de la base de datos.
     */
    public function destroy(Justificacion $justificacione)
    {
        $justificacione->delete();

        return redirect()->route('justificaciones.index')->with('success', '¡Justificación eliminada exitosamente!');
    }
}