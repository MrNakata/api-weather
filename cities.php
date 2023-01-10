<?php
/*
    cities
 */
require_once("include/fonctions.inc.php");
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