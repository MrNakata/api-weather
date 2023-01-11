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
            if (cityId > -1) {
                // Supprime la ville de la bdd
                deleteCity(cityId);
            } else {
                // Supprime la ligne uniquement
                deleteCityRow(cityId)
            }
        } else if (classNames.indexOf("btn-save") != -1) {
            // Ajout d'une nouvelle ville
            addCity(cityId);
        }
    }
});
$weatherCity.addEventListener("click", event => {
    const $this    = event.target;
    let weatherId  = $this.dataset.weatherid;
    let classNames = $this.className;
    if (classNames.indexOf("btn-delete") != -1) {
        if (weatherId > -1) {
            // Suppression de la fiche météo
            deleteWeather(weatherId);
        } else {
            deleteWeatherRow(weatherId);
        }
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
 * Clic sur un bouton Ajouter
 * Ajoute une nouvelle ligne
 */
const $btnAdd = document.querySelectorAll(".btn-add");
$btnAdd.forEach(element => {
    element.addEventListener("click", event => {
        let id = event.target.id
        if (id == "btn-add-city") {
            addCityRow();
        } else if (id == "btn-add-weather") {
            addWeatherRow();
        }
    })
});

var nbCityAdded = 0;
/**
 * Création via ajax de la nouvelle ligne html
 */
async function addCityRow () {
    let formData = new FormData();
    formData.append("mode", "add_city_row");
    formData.append("nbCityAdded", --nbCityAdded);
    fetch('ajax.php', {
        method: "POST",
        body  : formData,
    })
    .then(response => response.json()) 
    .then(json => {
        $allCities.insertAdjacentHTML("afterBegin",json.html);
    })
    .catch(err => console.log(err));
}
var nbWeatherAdded = 0;
/**
 * Création via ajax de la nouvelle ligne html
 */
async function addWeatherRow () {
    let formData = new FormData();
    formData.append("mode", "add_weather_row");
    formData.append("nbWeatherAdded", --nbWeatherAdded);
    fetch('ajax.php', {
        method: "POST",
        body  : formData,
    })
    .then(response => response.json()) 
    .then(json => {
        $weatherCity.insertAdjacentHTML("afterBegin",json.html);
    })
    .catch(err => console.log(err));
}
/**
 * Ajout d'une nouvelle ville en bdd
 */
async function addCity (cityId) {
    if (cityId != undefined && cityId < 0) {
        let cityLabel = document.getElementById("city-label"+cityId).value;
        let country   = document.getElementById("country"+cityId).value;
        let formData  = new FormData();
        formData.append("mode", "add_city");
        formData.append("city_label", cityLabel);
        formData.append("country", country);
        fetch('ajax.php', {
            method: "POST",
            body  : formData,
        })
        .then(response => response.json()) 
        .then(json => {
            console.log(json)
            if(json.added) {
                getCities();
            }
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
                deleteCityRow(cityId);       
            }
        })
        .catch(err => console.log(err));
    }
}
/**
 * Supprime la ligne html de la ville
 */
function deleteCityRow (cityId) {
    // Supprime la ligne
    document.querySelectorAll(".row[data-cityid='"+cityId+"']").forEach(element => {
        element.innerHTML = '';
    });
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
                deleteWeatherRow(weatherId);
            }
        })
        .catch(err => console.log(err));
    }
}
/**
 * Supprime la ligne html de la météo
 */
function deleteWeatherRow (weatherId) {
    // Supprime la ligne
    document.querySelectorAll(".row[data-weatherid='"+weatherId+"']").forEach(element => {
        element.innerHTML = '';
    });
}