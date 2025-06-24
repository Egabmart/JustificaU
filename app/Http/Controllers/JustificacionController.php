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
        $validatedData = $request->validate([
            'clase'        => 'required|string|max:255',
            'grupo'        => 'required|string|max:50',
            'hora'         => 'required', // <-- REGLA SIMPLIFICADA
            'reason'       => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'constancia'   => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['student_name'] = Auth::user()->name;
        $validatedData['student_id'] = Auth::user()->cif;

        if ($request->hasFile('constancia')) {
            $validatedData['constancia_path'] = $request->file('constancia')->store('constancias', 'public');
        }

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
            'hora' => 'required', // <-- REGLA SIMPLIFICADA
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
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