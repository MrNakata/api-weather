<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet"/>
    <link rel="stylesheet" href="include/style.css">
    <title>Ringover test - Mini API</title>
</head>
<body>
    <main>
        <h1>ringoweather api - dashboard</h1>
        <div class="wrap">
            <div class="container border">
                <h2>Toutes les villes <button type="button" name="btn-add-city" id="btn-add-city" class="btn btn-add" title="Ajouter une nouvelle ville">+</button></h2>
                <div class="row header">
                    <div class="col">ID</div>
                    <div class="col">Ville</div>
                    <div class="col">Pays</div>
                    <div class="col">Date de création</div>
                    <div class="col">Actions</div>
                </div>
                <div id="all-cities"></div>
            </div>
            <div class="container border">
                <h2>Prévisions météorologiques par ville <button type="button" name="btn-add-weather" id="btn-add-weather" class="btn btn-add" title="Ajouter une nouvelle fiche météo">+</button> <select name="select-city" id="select-city"></select></h2>
                <div class="row header">
                    <div class="col">Date</div>
                    <div class="col">Heure</div>
                    <div class="col">°C</div>
                    <div class="col">Temps</div>
                    <div class="col">Précip.</div>
                    <div class="col">Humidité</div>
                    <div class="col">Vent</div>
                    <div class="col">Actions</div>
                </div>
                <div id="weather-city"></div>
            </div>
        </div>
    </main>
    <script src="include/script.js"></script>
</body>
</html>