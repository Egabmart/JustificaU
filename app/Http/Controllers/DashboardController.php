<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra el panel de control con estadísticas.
     */
    public function index()
    {
        // Hacemos conteos usando el modelo Justificacion
        $totalCount = Justificacion::count();
        $approvedCount = Justificacion::where('status', 'Aprobada')->count();
        $pendingCount = Justificacion::where('status', 'Pendiente')->count();

        // Obtenemos las 5 justificaciones más recientes
        $recentJustificaciones = Justificacion::latest()->take(5)->get();

        // Pasamos todas las variables a la vista
        return view('dashboard', [
            'title' => 'Panel de Control',
            'totalCount' => $totalCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'recentJustificaciones' => $recentJustificaciones,
        ]);
    }
}