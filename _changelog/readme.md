User story no.1
Als Chef 
Wil ik recepten kunnen ontdekken, opslaan en delen
Zodat ik eenvoudig inspiratie vindt voor gerechten die passen bij mijn smaak, dieet en gelegenheid.

User story no.2
Als gebruiker
Wil ik een account aanmaken en inloggen
Zodat ik mijn favoriete recepten kan bewaren en persoonlijke voorkeuren kan instellen.

User story no.3
Als bezoeker
Wil ik een overzicht van recepten kunnen bekijken, filteren op keuken, maaltijdtype en dieet
Zodat ik snel gerechten kan vinden die passen bij mijn voorkeur.

User story no.4
Als gebruiker
Wil ik de ingrediënten, bereidingstijd, kooktijd en instructies van een recept kunnen zien
Zodat ik het gerecht stap voor stap kan creëren.


---
# Changelog 16/10/2025:

### - Updated app.blade.php in the layouts folder

### - Updated web.php with new routes and middleware

### - Added recipe_meals folder, with an index and show file

### - Added create.blade.php in the recipes folder

### - Added factories for Ingredients and Recipe_Meal

---

# Changelog 15/10/2025 at 12:30:

### - Added migrations for the following:

- ingredients, recipes, recipe_meal, meals, recipe_cuisine, cuisines, recipe_diet, diets, recipe_ingredient, seasons, recipe_allergy, allergies, favorites, recipe_season, ratings en ingredients.

### - Added factories for the following:

  - Ingredients & Recipe

### - Added the following controllers:

  - IngredientsController & RecipeController

### - Added the following models:

  - Ingredients & Recipe

### - Added views for the following:

####   - ingredients

    - index.blade.php
    - show.blade.php

####   - recipes

    - index.blade.php
    - show.blade.php

### - Added according routes in web.php
