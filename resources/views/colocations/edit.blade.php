<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier colocation</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 0 20px; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 10px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 8px 20px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 14px; }
        .back { color: #333; text-decoration: none; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>

<a href="{{ route('colocations.show', $colocation) }}" class="back">Retour</a>

<h1>Modifier la colocation</h1>

@if($errors->any())
    <ul class="error">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('colocations.update', $colocation) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="name" value="{{ old('name', $colocation->name) }}" required maxlength="255">
    </div>
    <button type="submit" class="btn">Modifier</button>
</form>

</body>
</html>