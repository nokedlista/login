<?php

require_once('DBCities.php');
$db = new DBCities();
$entities = $db->getAllCities();
$csvFile = fopen('adatok.csv', 'w');
fputcsv($csvFile, ['county', 'zip_code', 'city']);
foreach ($entities as $entity) {
    fputcsv($csvFile, $entity);
}
fclose($csvFile);
header("Location: ./index.php");
exit();
