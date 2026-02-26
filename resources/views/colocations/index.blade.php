<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Colocations</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 10px; }
        .colocation-card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .btn { padding: 8px 15px; background: #333; color: white; text-decoration: none; border-radius: 4px; }
        .btn-red { background: #c0392b; border: none; cursor: pointer; color: white; padding: 8px 15px; border-radius: 4px; }
        .statut { color: green; font-weight: bold; }
        nav { display: flex; justify-content: flex-end; margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
    </style>
</head>
<body>

<nav>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-red">Se déconnecter</button>
    </form>
</nav>

<h1>Mes Colocations</h1>

<a href="{{ route('colocations.create') }}" class="btn">Créer une colocation</a>

<br><br>

@if($colocations->isEmpty())
    <p>Aucune colocation trouvée.</p>
@endif

@foreach($colocations as $colocation)
    <div class="colocation-card">
        <h3>{{ $colocation->name }}</h3>
        <p>Statut: <span class="statut">{{ $colocation->status }}</span></p>
        <a href="{{ route('colocations.show', $colocation) }}" class="btn">Voir</a>
    </div>
@endforeach

</body>
</html>