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

# Changelog 31/10/2025

### Users now have the ability to delete their own recipes

### More filtering options are now available on the recipes page

### bugfixes and language parity

---

# Changelog 30/10/2025

## A lot of important files apparently haven't been uploaded yet, so here they are.

### - Added a My Recipes page, where you can easily see your own recipes. Here you can switch a toggle per recipe if it should pe published or not. Delete option coming very soon

### - Added a favorite button on recipes that are not your own. Being able to see your favorites in a list will be coming soon

### - Added the following controllers:
- AboutController.php, RecipeController.php, RecipeEquipmentController.php, RecipeMealController.php, RecipeStepController.php and RecipeTipController.php

### - Added the following models:
- Ingredient.php, Recipe.php, Recipe_Meal.php, RecipeEquipment.php, RecipeStep.php and RecipeTip.php

### - Added the following Factories:
- RecipeEquipmentFactory.php, RecipeStepFactory.php and RecipeTip.php

### - Added a lot of migrations. There's 19 new migrations total

### - Added a new stylesheet in app.blade.php in the layouts folder. This is to load the heart emoji's for favoriting recipes

### - Changed some navigation, Dashboard will be fazed out completely and recipes will be the new main page

### - Apparently edit.blade.php in the profile folder has been changed, i have no idea what physically changed there

### - Changed some styling in a lot of pages, most notably in create.blade.php

### - If no recipes are found in the recipes index page, it now says so

### - Added and Updated routes in web.php


# - Admin options have been added
New admin functions have been added, such as deleting recipes, as well as users.

### - Added AdminController.php and AdminMiddleware.php,

### - Updated bootstrap/app.php, added admin middleware

### - Added admin views, such as dashboard, recipes and users


---


# Changelog 29/10/2025

after almost 2 weeks of no updates (vacation and just straight up forgetting to publish), a new version has  rolled out.

### - Updated User.php with the function favoriteRecipes, though it has no real use case yet

### - Updated views (index & show) for ingredients, but those views will most likely be removed with the next update

### - Updated create page for recipes, mostly added missing functions and styling

### - Created edit and favorites pages for recipes. favorites currently has no function

### - Updated index page for recipes, added card images, and new styling and bugfixes

### - Updated show page for recipes, added all of the missing content you couldn't see before.

### - Created a searchbar in recipes, lets you look for recipe names or certain ingredients.

### - New routes and Middleware added in web.php

## - Important: A couple of views and routes will be removed in the next published version.


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
