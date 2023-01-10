const $allCities   = document.getElementById("all-cities");
const $selectCity  = document.getElementById("select-city");
const $weatherCity = document.getElementById("weather-city");
getCities();
/**
 * Récup de toutes les villes
 */
async function getCities () {
    let formData = new FormData();
    formData.append("mode", "get_cities");
    fetch('ajax.php', {
        method: "POST",
        body  : formData,
    })
    .then(response => response.json()) 
    .then(json => {
        $allCities.innerHTML = json.html;
        $selectCity.innerHTML = json.list;
    })
    .catch(err => console.log(err));
}
/**
 * Ecoute du select:option d'une ville
 */
$selectCity.addEventListener("change", event => {
    getWeather(event.target.value);
});

/**
 * Ecoute du clic sur une ligne d'une ville
 * pour afficher les fiches météos liées
 */
$allCities.addEventListener("click", event => {
    const $this    = event.target;
    let cityId     = $this.dataset.cityid;
    let classNames = $this.className;
    if (classNames.indexOf("btn") == -1) {
        // Affichage de la météo de la ville
        getWeather(cityId);
        $selectCity.value = cityId;
    } else {
        if (classNames.indexOf("btn-edit") != -1) {
            // Clic sur btn modifier
        } else if (classNames.indexOf("btn-delete") != -1) {
            // Clic sur btn supprimer
            deleteCity(cityId);
        }
    }
});
$weatherCity.addEventListener("click", event => {
    const $this    = event.target;
    let weatherId  = $this.dataset.weatherid;
    let classNames = $this.className;
    if (classNames.indexOf("btn-delete") != -1) {
        // Suppression de la fiche météo
        deleteWeather(weatherId);
    }
})
/**
 * Affichage de la météo d'une ville
 */
async function getWeather (cityId) {
    if (cityId != undefined) {
        let formData = new FormData();
        formData.append("mode", "get_weather");
        formData.append("cityId", cityId);
        fetch('ajax.php', {
            method: "POST",
            body  : formData,
        })
        .then(response => response.json()) 
        .then(json => {
            $weatherCity.innerHTML = json.html;
        })
        .catch(err => console.log(err));
    }
}

/**
 * Suppression d'une ville et des fiches météos liées
 */
async function deleteCity (cityId) {
    if (cityId != undefined) {
        let formData = new FormData();
        formData.append("mode", "delete_city");
        formData.append("cityId", cityId);
        fetch('ajax.php', {
            method: "POST",
            body  : formData,
        })
        .then(response => response.json()) 
        .then(json => {
            if (json.deleted) {
                // Supprime la ligne
                document.querySelectorAll(".row[data-cityid='"+cityId+"']").forEach(element => {
                    element.innerHTML = '';
                });
            }
        })
        .catch(err => console.log(err));
    }
}

/**
 * Suppression d'une fiche météo
 */
async function deleteWeather (weatherId) {
    if (weatherId != undefined) {
        let formData = new FormData();
        formData.append("mode", "delete_weather");
        formData.append("weatherId", weatherId);
        fetch('ajax.php', {
            method: "POST",
            body  : formData,
        })
        .then(response => response.json()) 
        .then(json => {
            if (json.deleted) {
                // Supprime la ligne
                document.querySelectorAll(".row[data-weatherid='"+weatherId+"']").forEach(element => {
                    element.innerHTML = '';
                });
            }
        })
        .catch(err => console.log(err));
    }
}