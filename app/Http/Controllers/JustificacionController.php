<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // <--- AÑADIR para manejar archivos

class JustificacionController extends Controller
{
    public function index()
    {
        $justificaciones = Justificacion::latest()->paginate(10);
        return view('justificaciones.index', compact('justificaciones'));
    }

    public function create()
    {
        return view('justificaciones.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'clase'        => 'required|string|max:255',
            'grupo'        => 'required|string|max:50',
            'hora'         => 'required|date_format:H:i',
            'reason'       => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'constancia'   => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048', // Validación del archivo
        ]);

        $validatedData['student_name'] = auth()->user()->name;
        $validatedData['student_id'] = auth()->user()->cif; // O `id`, depende cómo se llame en tu modelo User

        if ($request->hasFile('constancia')) {
            $validatedData['constancia_path'] = $request->file('constancia')->store('constancias', 'public');
        }

        Justificacion::create($validatedData);

        return redirect()->route('justificaciones.index')->with('success', '¡Justificación creada exitosamente!');
    }

    public function show(Justificacion $justificacione)
    {
        //
    }

    public function edit(Justificacion $justificacione)
    {
        return view('justificaciones.edit', compact('justificacione'));
    }

    public function update(Request $request, Justificacion $justificacione)
    {
        $validatedData = $request->validate([
            'clase'        => 'required|string|max:255',
            'grupo'        => 'required|string|max:50',
            'hora'         => 'required|date_format:H:i',
            'reason'       => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'constancia'   => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('constancia')) {
            // Borrar el archivo anterior si existe
            if ($justificacione->constancia_path) {
                Storage::disk('public')->delete($justificacione->constancia_path);
            }
            // Subir el nuevo archivo
            $validatedData['constancia_path'] = $request->file('constancia')->store('constancias', 'public');
        }

        $justificacione->update($validatedData);

        return redirect()->route('justificaciones.index')->with('success', '¡Justificación actualizada exitosamente!');
    }

    public function destroy(Justificacion $justificacione)
    {
        // Borrar el archivo asociado si existe
        if ($justificacione->constancia_path) {
            Storage::disk('public')->delete($justificacione->constancia_path);
        }
        $justificacione->delete();
        return redirect()->route('justificaciones.index')->with('success', '¡Justificación eliminada exitosamente!');
    }
}