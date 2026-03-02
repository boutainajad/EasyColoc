<!DOCTYPE html>
<html>
<head>
    <title>Depenses</title>
</head>
<body>

<h1>Depenses</h1>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

@if($errors->any())
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

@foreach($expenses as $expense)
    <div>
        <p>{{ $expense->title }} - {{ $expense->amount }} - {{ $expense->date }}</p>
        <p>Payé par: {{ $expense->paidBy->name }}</p>
        <p>Categorie: {{ $expense->category->name }}</p>
        <form method="POST" action="{{ route('expenses.destroy', $expense) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Supprimer</button>
        </form>
    </div>
@endforeach

<form method="POST" action="{{ route('expenses.store', $colocation) }}">
    @csrf
    <input type="text" name="title" placeholder="Titre" required maxlength="255">
    <input type="number" name="amount" placeholder="Montant" step="0.01" min="0" required>
    <input type="date" name="date" required>

    <select name="category_id" required>
        <option value="">Choisir une categorie</option>
        @foreach($colocation->categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <select name="paid_by" required>
        <option value="">Choisir le payeur</option>
        @foreach($members as $membership)
            <option value="{{ $membership->user->id }}">{{ $membership->user->name }}</option>
        @endforeach
    </select>

    <button type="submit">Ajouter</button>
</form>

</body>
</html>