<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vármegyék</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="script.js"></script>
</head>

<body>
    <h1 class ='sticky'>Magyarország vármegyéi</h1>
    <?php
    require_once("DBCounties.php");
    require_once("DBCities.php");
    require_once('CsvTools.php');

    $countyMaker = new DBCounties();
    $cityMaker = new DBCities();

    $fileName = 'zip_codes.csv';
    $csvData = getCsvData($fileName);
    $cityMaker->fillCities($csvData);

    $countyMaker->fillCounties($csvData);
    $fileName = 'county_data.csv';
    $csvData = getCsvData($fileName);
    $countyMaker->fillCountiesWithCountyData($csvData);
    $cityMaker->fillCities($csvData);
    $out = $countyMaker->displayTable();
    echo $out;

    if (isset($_POST["btn-del"])) {
        $zip = $_POST["btn-del"];
        $cityMaker->delete($zip);
    }

    if (isset($_POST["btn-mod"])) {
        $id = $_POST["btn-mod"];
        $city = $_POST["newNameFromMod"];
        $county = $_POST["newCountyFromMod"];
        $zip = $_POST["newZipCodeFromMod"];

        $cityMaker->update($id, $zip, $city, $county);
        $modified = $cityMaker->getCityByZip($zip);

        if($modified['city'] != $city || $modified['county'] != $county || $modified['zip_code'] != $zip) {
            echo "<script>alert('Sikerertelen módosítás próbálja meg úja!');</script>";
        } else {
            echo "<script>alert('Sikereres módosítás!');</script>";
        }
    }

    if (isset($_POST["btn-new"])) {
        $name = $_POST["newCityName"];
        $code = $_POST["newCityPostalCode"];
        $county = $countyMaker->getCountyById($_POST["chosenCounty"]);
        if(!empty($name) && !empty($code))
        {
            $cityMaker->add($name, $code, $county);
        }
        else
        {
            echo "<script>alert('Töltse ki mindhárom mezőt!');</script>";
        }
    }
    ?>
    <div class='hozzaad'>
        <h2>Város hozzáadása</h2>
        <form method='post'>
            <p><a>Város neve:</a>
                <input type="text" id="newCityName" name='newCityName'>
            </p>
            <p><a>Város irányítószáma:</a>
                <input type="number" id="newCityPostalCode" name='newCityPostalCode'>
            </p>
            <p><a>Megye:</a>
            <select id='chosenCounty' name='chosenCounty'>
                <?php
                $counties = $countyMaker->getAllCounties();
                foreach ($counties as $county) {
                    echo "<option value='{$county['id']}'>{$county['county']}</option>";
                }
                ?>
            </select>
            </p>
            <input id='btn-new' name='btn-new' type="submit" value="Város felvétele"></p>
        </form>
    </div>

    <div class='kereses'>
        <br>
        <h2>Város keresése</h2>
        <input id="cityForSearch" type="text">
        <input type="button" id="btn-search" value="keresés" onclick="search()">
        <label for="lb-search"><p id="lb-search"></p></label>
    </div>

    <label for="modify"><div class='modosit' id="modify">
    </div></label>

    <div class='export'>
        <form method="post" action="export_db.php">
            <button type='submit' id="btn-export" name="btn-export" title="Export to .CSV">Export CSV</button>
        </form>
    </div>
    <div class='PDF'>
        <form method="post" action="downloadPDF.php">
            <button type='submit' id="btn-pdf" name="btn-pdf" title="Download PDF">Download PDF</button>
        </form>
    </div>
    

    

</body>

</html>