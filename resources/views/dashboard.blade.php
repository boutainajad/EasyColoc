<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        nav { display: flex; justify-content: flex-end; margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .btn { padding: 8px 15px; background: #333; color: white; text-decoration: none; border-radius: 4px; margin-left: 10px; }
        .btn-red { background: #c0392b; border: none; cursor: pointer; color: white; padding: 8px 15px; border-radius: 4px; }
    </style>
</head>
<body>

<nav>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-red">Se déconnecter</button>
    </form>
</nav>

<h1>Bonjour {{ Auth::user()->name }}</h1>

<a href="{{ route('colocations.index') }}" class="btn">Mes colocations</a>

</body>
</html>