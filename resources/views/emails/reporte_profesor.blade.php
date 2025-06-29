<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Justificaciones</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #0099a8; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px 0; }
        .details { background-color: #f9f9f9; padding: 15px; border: 1px solid #eee; border-radius: 3px; margin-bottom: 20px;}
        .student-list { list-style: none; padding: 0; }
        .student-list li { border-bottom: 1px solid #eee; padding: 10px 0; }
        .student-list li:last-child { border-bottom: none; }
        .motivo { margin-top: 5px; padding-left: 15px; font-style: italic; color: #555; }
        strong { color: #0099a8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reporte de Justificaciones</h1>
        </div>
        <div class="content">
            <p>Hola, {{ $infoReporte['profesor'] }},</p>
            <p>Este es un resumen de los estudiantes con justificaciones **aprobadas** para tu clase:</p>

            <div class="details">
                <p><strong>Clase:</strong> {{ $infoReporte['clase'] }}</p>
                <p><strong>Grupo:</strong> {{ $infoReporte['grupo'] }}</p>
            </div>

            <h3>Listado de Alumnos:</h3>
            <ul class="student-list">
                @forelse($justificacionesAprobadas as $justificacion)
                    <li>
                        <strong>{{ $justificacion->student_name }}</strong>
                        <div class="motivo">"{{ $justificacion->reason }}"</div>
                    </li>
                @empty
                    <li>No hay alumnos con justificaciones aprobadas para este reporte.</li>
                @endforelse
            </ul>

            <p>Este reporte ha sido generado automáticamente por el Sistema de Justificaciones UAM.</p>
        </div>
    </div>
</body>
</html>