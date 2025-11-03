<?php

namespace App\Http\Controllers;

use App\Models\Justificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Notifications\JustificacionNotifier;

class ReporteController extends Controller
{
    /**
     * Muestra la página principal de reportes.
     */
    public function index()
    {
        // Unimos la tabla de justificaciones con la de usuarios para poder acceder a la carrera.
        $reportes = DB::table('justificacions')
                        ->join('users', 'justificacions.user_id', '=', 'users.id')
                        ->select('users.carrera', 'justificacions.clase', 'justificacions.grupo', 'justificacions.profesor', DB::raw('count(*) as total_alumnos'))
                        ->groupBy('users.carrera', 'justificacions.clase', 'justificacions.grupo', 'justificacions.profesor')
                        ->orderBy('users.carrera')
                        ->orderBy('justificacions.clase')
                        ->get();

        return view('reportes.index', [
            'reportes' => $reportes,
        ]);
    }

    /**
     * Obtiene los detalles de los alumnos para un reporte específico.
     */
    public function getAlumnosPorReporte(Request $request)
    {
        $validated = $request->validate([
            'clase' => 'required|string',
            'grupo' => 'required|string',
            'profesor' => 'required|string',
        ]);

        $alumnos = Justificacion::where('clase', $validated['clase'])
                                ->where('grupo', $validated['grupo'])
                                ->where('profesor', $validated['profesor'])
                                ->select('student_name', 'status')
                                ->get();

        return response()->json($alumnos);
    }

    /**
     * Envía el reporte de justificaciones aprobadas al profesor.
     */
    public function enviarReporte(Request $request)
    {
        // 1. Validamos todos los datos que vienen del JavaScript, incluyendo la carrera
        $validated = $request->validate([
            'carrera' => 'required|string',
            'clase' => 'required|string',
            'grupo' => 'required|string',
            'profesor' => 'required|string',
        ]);

        // 2. Buscamos el AÑO al que pertenece la clase para poder hacer una búsqueda precisa
        $anioDeLaClase = null;
        $datosAcademicos = config('academic.' . $validated['carrera'], []);

        foreach ($datosAcademicos as $anio => $clasesDelAnio) {
            if (array_key_exists($validated['clase'], $clasesDelAnio)) {
                $anioDeLaClase = $anio;
                break; // Encontramos el año, salimos del bucle
            }
        }

        if (!$anioDeLaClase) {
            return response()->json(['success' => false, 'message' => 'Error Crítico: No se pudo determinar el año académico de la clase.'], 404);
        }

        // 3. Búsqueda PRECISA Y DIRECTA del correo del profesor usando la carrera, año, clase y grupo
        $profesorInfo = config("docentes.{$validated['carrera']}.{$anioDeLaClase}.{$validated['clase']}.{$validated['grupo']}");

        // Verificación robusta de que encontramos el correo
        if (!$profesorInfo || empty($profesorInfo['email'])) {
            return response()->json(['success' => false, 'message' => 'Error: No se encontró el correo del profesor para esta combinación específica de carrera, clase y grupo.'], 404);
        }

        // 4. Obtener las justificaciones APROBADAS que coincidan con todos los criterios
        $justificacionesAprobadas = Justificacion::where('clase', $validated['clase'])
                                                ->where('grupo', $validated['grupo'])
                                                ->where('profesor', $validated['profesor'])
                                                ->whereHas('user', function ($query) use ($validated) { // Verificamos la carrera del usuario
                                                    $query->where('carrera', $validated['carrera']);
                                                })
                                                ->where('status', Justificacion::STATUS_APROBADA)
                                                ->get();

        if ($justificacionesAprobadas->isEmpty()) {
             return response()->json(['success' => false, 'message' => 'No hay justificaciones aprobadas para enviar en este reporte.'], 400);
        }

        // 5. Enviar el correo
        try {
            app(JustificacionNotifier::class)->notify(null, [
                'event' => 'report_requested',
                'recipient' => 'oamartinez901@gmail.com',
                'justificaciones' => $justificacionesAprobadas,
                'metadata' => $validated,
            ]);
        } catch (\Throwable $e) {
            Log::error('FALLO AL ENVIAR CORREO: ' . $e->getMessage()); // Log para el desarrollador
            return response()->json(['success' => false, 'message' => 'Error al conectar con el servicio de correo.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Reporte enviado exitosamente.']);
    }
}