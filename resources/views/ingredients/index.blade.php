<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recepten overzicht</title>
</head>
<body>
    <h1>Alle ingredienten</h1>
    <ul>
        @foreach($ingredients as $ingredient)
            <li>
                <a href="{{ route('ingredients.show', $ingredient->id) }}">
                    {{ $ingredient->name }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>
