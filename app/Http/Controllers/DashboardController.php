<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el panel de control con estadísticas.
     */
    public function index()
    {
        // Obtenemos el usuario autenticado
        $user = Auth::user();

        // Creamos una consulta base de justificaciones
        $query = Justificacion::query();

        // Si el usuario NO es administrador, filtramos la consulta por su ID
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Ahora, calculamos todas las estadísticas a partir de la consulta (ya sea la global o la filtrada)
        $totalCount = $query->count();
        $approvedCount = (clone $query)->where('status', 'Aprobada')->count();
        $pendingCount = (clone $query)->where('status', 'Pendiente')->count();
        $recentJustificaciones = (clone $query)->latest()->take(5)->get();

        // Pasamos todas las variables (ya filtradas) a la vista
        return view('dashboard', [
            'title' => 'Panel de Control',
            'totalCount' => $totalCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'recentJustificaciones' => $recentJustificaciones,
        ]);
    }
}