<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Liste des Groupes</h1>
    <ul>
        @foreach($groups as $group)
        <li>{{ $group->name }}</li> 
        @endforeach
    </ul>
    
</body>

</html>