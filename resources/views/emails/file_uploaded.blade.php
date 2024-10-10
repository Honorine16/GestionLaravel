<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoi de fichiers</title>
</head>

<body>
    <h1>Envoi de nouveaux fichiers</h1>
    <p>Un nouveau fichier a été envoyé dans votre groupe: {{ $file->original_name }}</p>
    <p>Tu peux le télécharger <a href="{{ asset('storage/' . $file->file_path) }}">ici</a>.</p>

</body>

</html>