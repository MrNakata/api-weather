<?php
/**
 * AJAX
 */

$json = [];
if (isset($_POST['mode'])) {
    require_once("include/fonctions.inc.php");

    if ($_POST['mode'] == 'get_cities') {
        ob_start();
?>
        <option value="">-- sélectionner une ville --</option>
        <?php
        $select = ob_get_contents();
        ob_clean();
        
        // Liste des villes
        $tCities = getCities();
        
        // Création de l'affichage
        foreach ($tCities as $city) {
            ob_start();
?>
            <option value="<?=$city['city_id']?>"><?=$city['city_label']?></option>
<?php
            $select .= ob_get_contents();
            ob_clean();
            $dataCityId = 'data-cityid="'.$city['city_id'].'"';
            ob_start();
?>
            <div class="row"<?=$dataCityId?>>
                <div class="col"<?=$dataCityId?>><?=$city['city_label']?></div>
                <div class="col"<?=$dataCityId?>><?=$city['country']?></div>
                <div class="col"<?=$dataCityId?>><?=date('d/m/Y H:i', strtotime($city['CREATION_DATE']))?></div>
                <div class="col">
                    <button type="button" name="btn-edit-city" id="btn-edit-city" class="btn btn-edit"<?=$dataCityId?>>Modifier</button> 
                    <button type="button" name="btn-delete-city" id="btn-delete-city" class="btn btn-delete"<?=$dataCityId?> title="Cliquez pour supprimer cette ligne">x</button> 
                </div>
            </div>
<?php
            $html .= ob_get_contents();
            ob_clean();
        }
        $json = [
            "html" => $html,
            "list" => $select,
            "post" => $_POST,
        ];

    } elseif ($_POST['mode'] == 'delete_city') {
        // Suppression d'une ville et des fiches météos liées
        $isDeleted = deleteCity($_POST['cityId']);
        $json = ['deleted' => $isDeleted];
        
    } elseif ($_POST['mode'] == 'delete_weather') {
        // Suppression d'une fiche météo
        $isDeleted = deleteWeather($_POST['weatherId']);
        $json = ['deleted' => $isDeleted];

    } elseif ($_POST['mode'] == 'add_city') {
    } elseif ($_POST['mode'] == 'add_city_row') {
        
        $nbCityAdded = $_POST['nbCityAdded'];
        $dataCityId = ' data-cityid="'.$nbCityAdded.'"';
        ob_start();
?>
        <div class="row"<?=$dataCityId?>>
            <div class="col"<?=$dataCityId?>><input type="text" name="city_label" id="city_label" value=""></div>
            <div class="col"<?=$dataCityId?>><input type="text" name="country" id="country" value=""></div>
            <div class="col"<?=$dataCityId?>><?= date("Y-m-d H:i:s")?></div>
            <div class="col">
                <button type="button" name="btn-save-city" id="btn-save-city" class="btn btn-save"<?=$dataCityId?>>Ajouter</button> 
                <button type="button" name="btn-delete-city" id="btn-delete-city" class="btn btn-delete"<?=$dataCityId?> title="Cliquez pour supprimer cette ligne">x</button> 
            </div>
        </div>
<?php
        $html .= ob_get_contents();
        ob_clean();
        $json = [
            "html" => $html,
        ];

    } elseif ($_POST['mode'] == 'add_weather_row') {
        
        $nbWeatherAdded = $_POST['nbWeatherAdded'];
        $dataWeatherId  = ' data-weatherid="'.$nbWeatherAdded.'"';
        ob_start();
?>
       <div class="row"<?=$dataWeatherId?>>
            <div class="col"><input type="text" name="temperature" id="temperature" value=""></div>
            <div class="col"><input type="text" name="weather" id="weather" value=""></div>
            <div class="col"><input type="text" name="precipitation" id="precipitation" value=""></div>
            <div class="col"><input type="text" name="humidity" id="humidity" value=""></div>
            <div class="col"><input type="text" name="wind" id="wind" value=""></div>
            <div class="col"><input type="date" name="date" id="date" value="<?= date("Y-m-d")?>"></div>
            <div class="col">
                <button type="button" name="btn-save-weather" id="btn-save-weather" class="btn btn-save"<?=$dataWeatherId?>>Ajouter</button> 
                <button type="button" name="btn-delete" id="btn-delete" class="btn btn-delete"<?=$dataWeatherId?> title="Cliquez pour supprimer cette ligne">x</button>
            </div>
        </div>
<?php
        $html .= ob_get_contents();
        ob_clean();
        $json = [
            "html" => $html,
        ];
    } elseif ($_POST['mode'] == 'get_weather') {
        
        // Liste de la météo par ville
        $tCityWeather = getCityWeather($_POST['cityId']);
        ob_clean();
        if (count($tCityWeather)>0) {
            $tDates = [];
            $tmpDate = '';
            foreach ($tCityWeather as $weather) {
                $dataWeatherId = 'data-weatherid="'.$weather['weather_id'].'"';
                ob_start();
                ?>
                    <div class="row"<?=$dataWeatherId?>>
                        <div class="col"><?=date('d/m/Y', strtotime($weather['date']))?></div>
                        <div class="col"><?=getWeatherHourText(date('H', strtotime($weather['date'])))?></div>
                        <div class="col"><?=$weather['temperature']?></div>
                        <div class="col"><?=$weather['weather']?></div>
                        <div class="col"><?=$weather['precipitation']?></div>
                        <div class="col"><?=$weather['humidity']?></div>
                        <div class="col"><?=$weather['wind']?></div>
                        <div class="col">
                            <button type="button" name="btn-delete" id="btn-delete" class="btn btn-delete"<?=$dataWeatherId?> title="Cliquez pour supprimer cette ligne">x</button>
                        </div>
                    </div>
<?php
                $html .= ob_get_contents();
                ob_clean();
            }
        } else {
            ob_start();
?>
            <div class="col">Aucune donnée</div>
<?php
            $html .= ob_get_contents();
            ob_clean();
        }
        $json = [
            "html" => $html,
        ];
    }
    
}
echo json_encode($json);