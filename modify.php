<?php
require_once 'DBCities.php';

if (isset($_POST['zipId'])) {

    $zipId = $_POST['zipId'];
    $dbCities = new DBCities();

    if (isset($zipId)) {
        $city = $dbCities->getCityByZip($zipId);
        
        echo"<h2>Város módosítása</h2>
            <form method='POST'>
                <p><a>Város: </a><input type='text' value='{$city['city']}' name='newNameFromMod'></input></p>
                <p><a>Megye: </a><input type='text' value='{$city['county']}' name='newCountyFromMod'></input></p>
                <p><a>Irányító szám: </a><input type='text' value='{$city['zip_code']}' name='newZipCodeFromMod'></input></p>
                <button type='submit' id='btn-mod' name='btn-mod' value='{$zipId}'>módosítás</button>
            </form>";
    }
} else {
    echo "semmi nem jó";
}
