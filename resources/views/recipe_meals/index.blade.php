<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><Recepten_maaltijden overzicht></title>
</head>
<body>
<h1>Alle recept maaltijden</h1>
<ul>
    @foreach($recipe_meals as $recipe_meal)
        <li>
            <a href="{{route('recipe_meals.show', $recipe_meal->id)}}">
                {{$recipe_meal->title}}
            </a>
        </li>
    @endforeach
</ul>
</body>
</html>
