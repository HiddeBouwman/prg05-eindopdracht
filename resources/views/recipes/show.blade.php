<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$recipe->title}}</title>
</head>
<body>
    <h1>{{$recipe->title}}</h1>
    <p><strong>Beschrijving:</strong>{{$recipe->description}}</p>
    <p><strong>Bereiding:</strong>{{$recipe->instructions}}</p>
    <p><strong>Bereidingstijd:</strong>{{$recipe->prep_time}}</p>
    <p><strong>Kooktijd:</strong>{{$recipe->cook_time}}</p>
</body>
</html>
