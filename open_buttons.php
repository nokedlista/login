<?php
require_once("DBCities.php");

if(isset($_POST['selectedCounty'])) {

    $selectedCounty = $_POST['selectedCounty'];

    if(isset($selectedCounty)){
        $dbCities = new DBCities();
        $abc = $dbCities->getABCbyCounty($selectedCounty);
        $result = "<td colspan='5'>";
        foreach($abc as $char)
        {
            $result .= "
                    <button onclick='citisList(\"{$char}\",\"{$selectedCounty}\")' id='abc' name='$char'>
                    $char
                    </button>
            ";
        }
        $result .= "
        </td>";

        echo $result;
    }
}   