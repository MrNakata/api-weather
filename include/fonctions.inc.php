<?php
require_once("include/connexion.inc.php");
/**
 * FONCTIONS
 */
/**
 * Récup un id dans l'url
 * ex : cities/1 ou cities/1/weather/2
 * @param string nom du paramètre
 * @return int|null
 */
function getUrlId (string $param) {
    $tUri = explode('/', $_SERVER['REQUEST_URI']);
    foreach ($tUri as $key => $value) {
        if ($value == $param) {
            $nextId = $key+1;
            if (isset($tUri[$nextId]) && !empty($tUri[$nextId])) {
                return intval($tUri[$nextId]);
            }
        }
    }
    return null;
}
/**
 * Récup les données des villes ou d'une ville
 * @param int $cityId id de la ville
 * @return array
 */
function getCities (int $cityId = null):array {
    global $pdo;
    $q = "SELECT * FROM city";
    if (isset($cityId)){
        // Ajout condition pour remonter une seule ville si un id a été passé
        $q .= " WHERE city_id = :city_id LIMIT 1";
    } else {
        $q .= " ORDER BY city_label ASC";
    }
    $select = $pdo->prepare($q);
    
    if (isset($cityId)){
        // bind l'id
        $select->bindValue(':city_id', $cityId, PDO::PARAM_INT);
    }
    $select->execute();
    $tDatas = [];
    if ($select->rowCount() > 0) {
        while ($city = $select->fetch(PDO::FETCH_ASSOC)) {
            array_push($tDatas, $city) ;
        }
    }
    return $tDatas;
}

/**
 * Récup la météo d'une ville
 * @param int $cityId id d'une ville
 * @return array
 */
function getCityWeather (int $cityId):array {
    global $pdo;
    if ($cityId == null) return [];

    $select = $pdo->prepare("SELECT * FROM weather WHERE city_id = :city_id");
    
    if (isset($cityId)){
        // bind l'id
        $select->bindValue(':city_id', $cityId, PDO::PARAM_INT);
    }
    $select->execute();
    $tDatas = [];
    if ($select->rowCount() > 0) {
        while ($city = $select->fetch(PDO::FETCH_ASSOC)) {
            array_push($tDatas, $city) ;
        }
    }
    return $tDatas;
}
/**
 * Renvoie le texte de la période de la journée selon l'heure
 * @param int $hour 
 * @return string
 */
function getWeatherHourText ($hour) {
    if ($hour <= 6) {
        return 'MATIN';
    } else if ($hour <= 12) {
        return 'MIDI';
    } else {
        return 'SOIR';
    }
}
/**
 * Ajout d'une nouvelle ville
 */
function addCity () {
    global $pdo;
    $country   = $_POST['country'];
    $cityLabel = $_POST['city_label'];
    $insert    = $pdo->prepare("INSERT INTO city (country, city_label, CREATION_DATE) VALUES (:country, :city_label , NOW())");
    $insert->bindValue(":country", $country, PDO::PARAM_STR);
    $insert->bindValue(":city_label", $cityLabel, PDO::PARAM_STR);
    $insert->execute();
    return $insert->rowCount() > 0;
}
/**
 * Suppression d'une ville et des fiches météos liées
 * @param int $cityId
 * @return boolean
 */
function deleteCity (int $cityId) {
    global $pdo;
    if ($cityId == null) return false;
    $delete = $pdo->prepare("DELETE FROM weather WHERE city_id = :city_id");
    $delete->bindValue(":city_id", $cityId, PDO::PARAM_INT);
    $delete->execute();
    $delete = $pdo->prepare("DELETE FROM city WHERE city_id = :city_id");
    $delete->bindValue(":city_id", $cityId, PDO::PARAM_INT);
    $delete->execute();
    return $delete->rowCount() > 0;
}
/**
 * Suppression d'une fiche météo
 * @param int $weatherId
 * @return boolean  
 */
function deleteWeather (int $weatherId) {
    global $pdo;
    if ($weatherId == null) return false;
    $delete = $pdo->prepare("DELETE FROM weather WHERE weather_id = :weather_id");
    $delete->bindValue(":weather_id", $weatherId, PDO::PARAM_INT);
    $delete->execute();
    return $delete->rowCount() > 0;
}
/**
 * Ajout d'une nouvelle fiche météo pour une ville
 */
function addCityWeather () {
    global $pdo;
    $cityId        = $_POST['cityId'];
    $temperature   = $_POST['temperature'];
    $weather       = $_POST['weather'];
    $precipitation = $_POST['precipitation'];
    $humidity      = $_POST['humidity'];
    $wind          = $_POST['wind'];
    $date          = $_POST['date'];
    $q = "INSERT INTO weather (city_id, temperature, weather, precipitation, humidity, wind, date) VALUES (:city_id, :temperature, :weather, :precipitation, :humidity, :wind, :date)";
    $insert  = $pdo->prepare($q);
    $insert->bindValue(":city_id", $cityId, PDO::PARAM_INT);
    $insert->bindValue(":temperature", strval($temperature), PDO::PARAM_STR);
    $insert->bindValue(":weather", $weather, PDO::PARAM_STR);
    $insert->bindValue(":precipitation", strval($precipitation), PDO::PARAM_STR);
    $insert->bindValue(":humidity", strval($humidity), PDO::PARAM_STR);
    $insert->bindValue(":wind", $wind, PDO::PARAM_INT);
    $insert->bindValue(":date", $date, PDO::PARAM_STR);
    $insert->execute();
    return $insert->rowCount() > 0;
}

function getSelectHour ($weatherId) {
    $tHourText = [
        '06:00:00' => 'MATIN',
        '12:00:00' => 'MIDI',
        '16:00:00' => 'SOIR',
    ];
    $select = '<select name="hour" id="hour'.$weatherId.'">';
    foreach ($tHourText as $hour => $text) {
        $select .= '<option value="'.$hour.'">'.$text.'</option>';
    }
    $select .= '</select>';
    return $select;
}
function getSelectWeather ($weatherId) {
    $tWeatherText = ["SUNNY", "RAINY", "WINDY", "FOGGY", "SNOW", "HAIL", "SHOWER", "LIGHTNING", "RAINDBOW", "HURRICANE"];
    asort($tWeatherText);
    $select = '<select name="weather" id="weather'.$weatherId.'">';
    foreach ($tWeatherText as $text) {
        $select .= '<option value="'.$text.'">'.$text.'</option>';
    }
    $select .= '</select>';
    return $select;
}