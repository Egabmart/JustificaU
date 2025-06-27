<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resolución de Justificación</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #0099a8; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px 0; }
        .details { background-color: #f9f9f9; padding: 15px; border: 1px solid #eee; border-radius: 3px; }
        strong { color: #0099a8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Justificaciones UAM</h1>
        </div>
        <div class="content">
            <p>Hola, {{ $justificacion->student_name }},</p>
            <p>Te notificamos que tu solicitud de justificación ha sido resuelta. A continuación, los detalles:</p>

            <div class="details">
                <p><strong>Clase:</strong> {{ $justificacion->clase }} (Grupo: {{ $justificacion->grupo }})</p>
                <p><strong>Profesor:</strong> {{ $justificacion->profesor }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($justificacion->fecha)->format('d/m/Y') }}</p>
                <p><strong>Horario:</strong> de {{ \Carbon\Carbon::parse($justificacion->hora_inicio)->format('h:i A') }} a {{ \Carbon\Carbon::parse($justificacion->hora_fin)->format('h:i A') }}</p>
                <hr>
                <p><strong>Estado final:</strong> APROBADA</p>
            </div>

            <p>Esta notificación ha sido generada automáticamente. Para más detalles, por favor ingresa al portal.</p>
            <p>Saludos,<br>Sistema de Justificaciones UAM</p>
        </div>
    </div>
</body>
</html>