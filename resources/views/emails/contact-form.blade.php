<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Mensaje de Contacto</title>
</head>
<body>
    <h2>Has recibido un nuevo mensaje de contacto:</h2>
    <p><strong>Nombre:</strong> {{ $contactData['name'] }}</p>
    <p><strong>Email:</strong> {{ $contactData['email'] }}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $contactData['message'] }}</p>
</body>
</html>