<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $colocation->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        h1, h2 { border-bottom: 1px solid #ddd; padding-bottom: 8px; }
        .btn { padding: 8px 15px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn-red { background: #c0392b; }
        .btn-orange { background: #e67e22; }
        .card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        input, select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-right: 5px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; }
        .back { color: #333; text-decoration: none; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>

<a href="{{ route('colocations.index') }}" class="back">Retour</a>

<h1>{{ $colocation->name }}</h1>
<p>Statut: {{ $colocation->status }}</p>

@if(session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

@if($errors->any())
    <ul class="error">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<h2>Membres</h2>
@foreach($members as $membership)
    <div class="card">
        <p>{{ $membership->user->name }} - {{ $membership->role }}</p>
    </div>
@endforeach

<h2>Inviter un membre</h2>
<form method="POST" action="{{ route('invitations.store', $colocation) }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit" class="btn">Inviter</button>
</form>

<h2>Categories</h2>
@foreach($colocation->categories as $category)
    <div class="card">
        <span>{{ $category->name }}</span>
        <form method="POST" action="{{ route('categories.destroy', $category) }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-red">Supprimer</button>
        </form>
    </div>
@endforeach

<form method="POST" action="{{ route('categories.store', $colocation) }}">
    @csrf
    <input type="text" name="name" placeholder="Nom de la categorie" required maxlength="255">
    <button type="submit" class="btn">Ajouter</button>
</form>

<h2>Depenses</h2>
@foreach($expenses as $expense)
    <div class="card">
        <p>{{ $expense->title }} - {{ $expense->amount }} DH - {{ $expense->date }}</p>
        <p>Paye par: {{ $expense->paidBy->name }} | Categorie: {{ $expense->category->name }}</p>
        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-red">Supprimer</button>
        </form>
    </div>
@endforeach

<form method="POST" action="{{ route('expenses.store', $colocation) }}">
    @csrf
    <input type="text" name="title" placeholder="Titre" required maxlength="255">
    <input type="number" name="amount" placeholder="Montant" step="0.01" min="0" required>
    <input type="date" name="date" required>
    <select name="category_id" required>
        <option value="">Categorie</option>
        @foreach($colocation->categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    <select name="paid_by" required>
        <option value="">Payeur</option>
        @foreach($members as $membership)
            <option value="{{ $membership->user->id }}">{{ $membership->user->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn">Ajouter</button>
</form>

<br><br>
<form method="POST" action="{{ route('colocations.leave', $colocation) }}" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-orange">Quitter la colocation</button>
</form>

<form method="POST" action="{{ route('colocations.cancel', $colocation) }}" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-red">Annuler la colocation</button>
</form>

</body>
</html>