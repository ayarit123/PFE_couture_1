<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un modèle</title>
</head>
<body>
    <h2>Ajouter un modèle</h2>
    <form action="{{ route('models.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="title">Nom du modèle :</label><br>
        <input type="text" id="title" name="title" value="{{ old('title') }}"><br>
        @error('title')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        
        <label for="description">Description :</label><br>
        <textarea id="description" name="description" rows="4" cols="50">{{ old('description') }}</textarea><br>
        @error('description')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        
        <label for="prix">Prix :</label><br>
        <input type="number" id="prix" name="prix" value="{{ old('prix') }}"><br>
        @error('prix')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        
        <label for="category_id">Catégorie :</label><br>
        <select id="category_id" name="id_categorie">
            @foreach($categories as $category)
                <option value="{{ $category->id }}"  >
                    {{ $category->name }}
                </option>
            @endforeach
        </select><br>
        @error('id_categorie')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        
        <label for="type">Type :</label><br>
        <select id="type" name="type">
            <option value="traditionnel" {{ old('type') == 'traditionnel' ? 'selected' : '' }}>Traditionnel</option>
            <option value="moderne" {{ old('type') == 'moderne' ? 'selected' : '' }}>Moderne</option>
        </select><br>
        @error('type')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        
        <label for="image">Image du modèle :</label><br>
        <input type="file" id="image" name="image_url"><br>
        @error('image_url')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        
        <input type="submit" role="button" value="Ajouter">
    </form>
</body>
</html>
