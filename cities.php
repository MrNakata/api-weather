<?php
/*
    API CITIES
 */
require_once("include/fonctions.inc.php");
/*
    INSTRUCTIONS
 */
$tDatas = [];
switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        $cityId = getUrlId('cities');
        $tDatas = (strpos($_SERVER['REQUEST_URI'], "weather") !== false)
        ? getCityWeather($cityId)// Récup la météo de la ville
        : getCities($cityId);// Récup de toutes les villes ou d'une ville
        break;

    case 'POST':
        $isAdded = (strpos($_SERVER['REQUEST_URI'], "weather") !== false) ? addCityWeather():addCity();
        $tDatas  = ($isAdded) 
            ? [
                'status'         => 200,
                'status_message' => 'La ville a bien été ajoutée.',
            ]
            : [
                'status'         => 400,
                'status_message' => 'La ville n\'a pas pu être ajoutée.',
            ];
        break;
        
    case 'DELETE':
        // Suppression d"une ville
        if (strpos($_SERVER['REQUEST_URI'], "weather") !== false) {
            $weatherId = getUrlId('weather');
            $isDeleted = deleteWeather($weatherId);
        } else {
            $cityId    = getUrlId('cities');
            $isDeleted = deleteCity($cityId);
        }
        $tDatas = ($isDeleted) 
            ? [
                'status'         => 200,
                'status_message' => 'La ville a bien été supprimée.',
            ]
            : [
                'status'         => 400,
                'status_message' => 'La ville n\'a pas pu être supprimée.',
            ];
        break;

    default:
        break;
}

header('Content-Type: application/json');
echo json_encode($tDatas, JSON_PRETTY_PRINT);