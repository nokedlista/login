<?php
require_once("DBCities.php");

if(isset($_POST['selectedCounty'])) {

    $selectedCounty = $_POST['selectedCounty'];

    if(isset($selectedCounty)){
        echo"<tr id='{$sor['county']}Id'>
            <td colspan='5' style='display:  none'></td>
        </tr>";
    }
} 