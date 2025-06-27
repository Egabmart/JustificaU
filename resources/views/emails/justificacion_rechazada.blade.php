<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resolución de Justificación</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #d32f2f; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; } /* Color rojo para rechazo */
        .content { padding: 20px 0; }
        .details { background-color: #f9f9f9; padding: 15px; border: 1px solid #eee; border-radius: 3px; }
        .rejection-reason { margin-top: 15px; padding: 10px; border: 1px solid #ffcdd2; background-color: #ffebed; border-radius: 3px; }
        strong { color: #d32f2f; }
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
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($justificacion->fecha)->format('d/m/Y') }}</p>
                <hr>
                <p><strong>Estado final:</strong> RECHAZADA</p>
            </div>

            <div class="rejection-reason">
                <p><strong>Motivo del rechazo:</strong></p>
                <p>{{ $justificacion->rejection_reason ?? 'No se especificó un motivo.' }}</p>
            </div>

            <p>Si consideras que esto es un error, por favor contacta a la administración académica.</p>
            <p>Saludos,<br>Sistema de Justificaciones UAM</p>
        </div>
    </div>
</body>
</html>