# Exercice 2 : PHP FullStack Internet et github sont autorisés. (3h30 min)

Le but de cet exercice est de faire avec votre environnement de développement favori : 

- Une mini API en PHP le plus simple possible , 
- Un dashboard sera créé avec un minimum de CSS (sans framework) , du AJAX, de l’ergonomie et du bon goût ,
- Un Dockerfile sera créé afin de packager l’application finale, 
- Bonus: Établir un environnement de développement avec Docker.

Un fichier SQL est fourni avec le test, il vous permettra de créer la base de donnée à utiliser sur un SGBD de type TiDB (ou MySQL). 

L’exercice sera rendu au format ZIP. 

Vous pouvez utiliser GIT en local sur le projet, mais il serait apprécié que le résultat de cet exercice ne se retrouve pas publié sur internet.

**GET** `/cities`

Permet de retourner la liste des villes de la base au format JSON. /cities

**POST** `/cities`

Permet de créer un nouvelle ville dans la base 

**DELETE** `/cities/:city_id`

Permet de supprimer un ville dans la base et ses fiches météo 

**GET** `/cities/:city_id/weather`

Permet de retourner la liste des fiches météo de la ville au format JSON. /

**POST** `cities/:city_id/weather`

Permet de créer une fiche météo dans la base 

**DELETE** `/cities/:city_id/weather/:weather_id`

Permet de supprimer une fiche météo dans la base