<!DOCTYPE html>
<html>
<head>
    <title>Nuevo mensaje de contacto</title>
</head>
<body>
    <h2>Nuevo mensaje de contacto</h2>
    <p><strong>Nombre:</strong> {{ $contact->name }}</p>
    <p><strong>Teléfono:</strong> {{ $contact->phone }}</p>
    <p><strong>Correo Electrónico:</strong> {{ $contact->email }}</p>
    <p><strong>Asunto:</strong> {{ $contact->case }}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $contact->message }}</p>
</body>
</html>
