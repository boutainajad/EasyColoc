<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
</head>
<body>

<h1>Categories</h1>

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

@foreach($colocation->categories as $category)
    <p>{{ $category->name }}
        <form method="POST" action="{{ route('categories.destroy', $category) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Supprimer</button>
        </form>
    </p>
@endforeach

<form method="POST" action="{{ route('categories.store', $colocation) }}">
    @csrf
    <input type="text" name="name" placeholder="Nom de la categorie" required maxlength="255">
    <button type="submit">Ajouter</button>
</form>

</body>
</html>