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
    require_once("CsvTools.php");
    require_once("login.php");
    require_once("login_if.php");
    require_once("createDB.php");

    $countyMaker = new DBCounties();
    $cityMaker = new DBCities();
    $createDB = new installDB();

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
    if (isset($_POST["btn-install"]))
    {
        $createDB->createDatabase();
        $login = new Login();
        $login->createLoginTable();
    }
    if (isset($_POST["btn-register"]))
    {
        showRegisterInterFace();
    }
    if (isset($_POST["btn-register-push"]))
    {
        $email = $_POST["email"];
        if(!$login->emailExists($email))
        {
            echo '<script>alert("Ilyen email cím már van!")</script>';
        }
        else
        {
            $login->registerPush($_POST["name"],$_POST["email"],$_POST["password1"]);
        }
    }
    if (isset($_POST["btn-login"]))
    {
        showLoginInterFace();
    }
    if (isset($_POST["btn-login-push"]))
    {
        $login->loginPush($_POST["email"],$_POST["password1"]);
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
    <form method="post" action="downloadPDF.php">
        <button class='PDF' type='submit' id="btn-pdf" name="btn-pdf" title="Download PDF">Download PDF</button>
    </form>
    <div class='db'>
        <form method="post">
            <button type='submit' id="btn-install" name="btn-install" title="Install">Install</button>
        </form>
    </div>
    <div class='register'>
        <form method="post">
            <button type='submit' id="btn-register" name="btn-register" title="Register" onclick='gombRejtes()'>Register</button>
        </form>
    </div>
    <div class='login'>
        <form method="post">
            <button type='submit' id="btn-login" name="btn-login" title="Log in" onclick='gombRejtes()'>Log in</button>
        </form>
    </div>
</body>

</html>