<?php
/*
    cities
 */
require_once("include/connexion.inc.php");
/*
    FUNCTIONS
 */
/**
 * Récup toutes les villes ou une ville
 * @param int $id
 * @return array
 */
function getCities (int $id = null):array {
    global $pdo;
    $q = "SELECT * FROM city";
    if (isset($id)){
        // Ajout condition pour remonter une seule ville si un id a été passé
        $q .= " WHERE city_id = :city_id LIMIT 1";
    }
    $select = $pdo->prepare($q);
    
    if (isset($id)){
        // bind l'id
        $select->bindValue(':city_id', $id, PDO::PARAM_INT);
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

/*
    INSTRUCTIONS
 */
switch($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        // Récup de toutes les villes ou d'une ville
        $id = (!empty($_GET["id"])) ? intval($_GET["id"]) : null;
        $tDatas = getCities($id);
        header('Content-Type: application/json');
        echo json_encode($tDatas, JSON_PRETTY_PRINT);
        break;

    case 'POST':
        addCity();
        break;

    default:
        break;
}