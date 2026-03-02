<!DOCTYPE html>
<html>
<head>
    <title>Invitation</title>
</head>
<body>

<h1>Vous avez recu une invitation</h1>

<a href="{{ route('invitations.accept', $token) }}">Accepter</a>
<a href="{{ route('invitations.refuse', $token) }}">Refuser</a>

</body>
</html>