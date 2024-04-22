<?php
require_once 'DB.php';

class DBCities extends DB
{
    public function createTableCities()
    {
        $query = 'CREATE TABLE IF NOT EXISTS cities(county varchar(35), zip_code int, city varchar(35))';
        return $this->mysqli->query($query);
    }


    public function fillCities(array $data)
    {
        $this->createTableCities();
        $result = $this->mysqli->query("SELECT * FROM cities");
        $row = $result->fetch_array(MYSQLI_NUM);
        $errors = [];
        $isFirst = true;
        if (empty($row)) {
            foreach ($data as $city) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }
                if (isset($city[0]) && isset($city[1]) && isset($city[2])) {
                    $insert = $this->mysqli->query("INSERT INTO cities VALUES ('$city[0]', '$city[1]', '$city[2]')");
                    if (!$insert) {
                        $errors[] = $city[0];
                    }
                }

            }
        }
        return $errors;
    }

    public function getAllCities()
    {
        $query = "SELECT * FROM cities";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);

    }

    public function getABCbyCounty($county)
    {
        $resoult = $this->mysqli->query("SELECT * FROM cities WHERE county = '$county'");
        $cities = $resoult->fetch_all(MYSQLI_ASSOC);
        $abc = [];
        foreach ($cities as $city) {
            $ch = strtoupper($city['city'][0]);
            if (!in_array($ch, $abc)) {
                $abc[] = $ch;
            }
        }

        return $abc;
    }

    public function getAbcCities($char, $county)
    {
        $result = $this->mysqli->query("SELECT * FROM cities WHERE city LIKE '$char%' AND county = '$county'");
        return $result;
    }

    public function delete(int $zip)
    {
        $query = "DELETE FROM cities WHERE zip_code = $zip";

        return $this->mysqli->query($query);
    }

    public function update($id, $zip, $city, $county)
    {
        $result = $this->mysqli->query("SELECT * FROM cities WHERE zip_code = '{$zip}'");
        $old = $this->mysqli->query("SELECT * FROM cities WHERE zip_code = '{$id}'");
        $zipSearchResoult = $result->fetch_array(MYSQLI_NUM);
        $oldInit = $old->fetch_array(MYSQLI_NUM);
        if (is_null($zipSearchResoult) || empty($zipSearchResoult)){
            $query = "UPDATE cities SET county = '$county', zip_code = '$zip', city = '$city' WHERE zip_code = $id;";
            return $this->mysqli->query($query);
        }
        else{
            if ($oldInit[2] == $zipSearchResoult[2] && $oldInit[0] == $zipSearchResoult[0]) {
                $query = "UPDATE cities SET county = '$county', zip_code = '$zip', city = '$city' WHERE zip_code = $id;";
                return $this->mysqli->query($query);
            } else {
                echo "<script>alert('Hiba! Nem lehetett módosítani a várost mert az irányító száma megegyezik egy másik településével.');</script>";
            }
        }
        
        
    }

    public function get($city)
    {
        $query = "SELECT * FROM cities WHERE city = '$city'";

        return $this->mysqli->query($query)->fetch_assoc();
    }

    public function getCityByZip($zip)
    {
        $query = "SELECT * FROM cities WHERE zip_code = '$zip'";

        return $this->mysqli->query($query)->fetch_assoc();
    }



    public function add($pName, $pCode, $pCounty)
    {
        $result = $this->mysqli->query("SELECT * FROM cities WHERE zip_code = '{$pCode}'");
        $postInit = $result->fetch_array(MYSQLI_NUM);
        if (is_null($postInit) || count($postInit) <= 3) 
        {
            $query = "INSERT INTO cities VALUES ('$pCounty[county]', '$pCode', '$pName')";
            return $this->mysqli->query($query);
        } 
        else 
        {
            echo "<script>alert('Hiba! Nem lehetett hozzá adni a várost mert az irányító száma megegyezik egy másik településsel.');</script>";
        }
    }

}