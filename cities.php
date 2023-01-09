<?php
/*
    cities
 */
require_once("include/connexion.inc.php");
/*
    FUNCTIONS
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

    $q = "SELECT * FROM weather WHERE city_id = :city_id";
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
 * Ajout d'une nouvelle ville
 */
function addCity () {
    global $pdo;
    $country = $_POST['country'];
    $label   = $_POST['label'];
    $q       = "INSERT INTO city (country, city_label, CREATION_DATE) VALUES (:country, :city_label: NOW())";
    $insert  = $pdo->prepare($q);
    $insert->bindValue(":country", $country, PDO::PARAM_STR);
    $insert->bindValue(":city_label", $label, PDO::PARAM_STR);
    $insert->execute();
}
/**
 * Ajout d'une nouvelle ville
 * @param int $cityId
 */
function addCityWeather (int $cityId) {
    global $pdo;
    $temperature   = $_POST['temperature'];
    $weather       = $_POST['weather'];
    $precipitation = $_POST['precipitation'];
    $humidity      = $_POST['humidity'];
    $wind          = $_POST['wind'];
    $q = "INSERT INTO weather (city_id, temperature, weather, precipitation, humidity, wind, date) VALUES (:city_id, :temperature, :weather, :precipitation, :humidity, :wind, NOW())";
    $insert  = $pdo->prepare($q);
    $insert->bindValue(":city_id", $cityId, PDO::PARAM_STR);
    $insert->bindValue(":temperature", $temperature, PDO::PARAM_STR);
    $insert->bindValue(":weather", $weather, PDO::PARAM_STR);
    $insert->bindValue(":precipitation", $precipitation, PDO::PARAM_STR);
    $insert->bindValue(":humidity", $humidity, PDO::PARAM_STR);
    $insert->bindValue(":wind", $wind, PDO::PARAM_STR);
    $insert->execute();
}
/*
    INSTRUCTIONS
 */
switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        $cityId = getUrlId('cities');
        $tDatas = (strpos($_SERVER['REQUEST_URI'], "weather") !== false)
        ? getCityWeather($cityId)// Récup la météo de la ville
        : getCities($cityId);// Récup de toutes les villes ou d'une ville
        
        header('Content-Type: application/json');
        echo json_encode($tDatas, JSON_PRETTY_PRINT);
        break;

    case 'POST':
        $cityId = getUrlId('cities');
        if (strpos($_SERVER['REQUEST_URI'], "weather") !== false) {
            addCityWeather($cityId);
        } else {
            addCity();
        }
        break;

    default:
        break;
}