<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowa wiadomość kontaktowa</title>
</head>
<body>
    <h1>Nowa wiadomość z formularza kontaktowego</h1>
    <p><strong>Imię:</strong> {{ $first_name }}</p>
    <p><strong>Nazwisko:</strong> {{ $last_name }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Numer Telefonu:</strong> {{ $phone }}</p>
    <p><strong>Treść wiadomości:</strong> {{ $message_content }}</p>
</body>
</html>