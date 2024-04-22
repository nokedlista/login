<?php
require_once 'DBCities.php';

if (isset($_POST['city'])) {

    $city = $_POST['city'];
    $dbCities = new DBCities();

    if (isset($city)) {
        try {
            $return = $dbCities->getCityByZip($city);
            if(empty($return) || $return['county'] == "") {
                throw new Exception();
            }
        } catch (Exception $e) {
            $return = $dbCities->get($city);
        }
        $result = "";
        if (!empty($return)) {
            for ($i = 0; $i < count($return) / 3; $i++) {
                $result .= "<p>{$return['zip_code']}, {$return['city']} {$return['county']} megye
                            </p>";
            }
        } else {
            $result .= "<p><a>Nincs ilyen nevű város az adatbázisban.</a>
                        <button onclick='modify(\"{$return['zip_code']}\")'>Módosítás</button></p>";
        }

        echo $result;
    }

} else {
    echo "semmi nem jó";
}
