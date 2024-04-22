<?php
require_once 'DB.php';
require_once 'DBCities.php';

class DBCounties extends DB
{
    public function createTableCounties()
    {
        $query = 'CREATE TABLE IF NOT EXISTS counties(id int, county varchar(35), capital varchar(35), population int, crest varchar(50), flag varchar(35))';
        return $this->mysqli->query($query);
    }


    public function fillCounties(array $data)
    {
        $this->createTableCounties();
        $result = $this->mysqli->query("SELECT * FROM counties");
        $row = $result->fetch_array(MYSQLI_NUM);
        $errors = [];
        $isFirst = true;
        $id = 0;
        if (empty($row)) {
            foreach ($data as $county) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }
                if ($county[0] != "") {
                    $init = $this->mysqli->query("SELECT county FROM counties WHERE county = '$county[0]'");
                    if (!$init->num_rows) {

                        $insert = $this->mysqli->query("INSERT INTO counties (id, county) VALUES ('$id', '$county[0]')");
                        $id = $id + 1;
                        if (!$insert) {
                            $errors[] = $county[0];
                        }
                        //csak feltöltésre

                    }
                }
            }
        }
        return $errors;
    }

    public function fillCountiesWithCountyData(array $data)
    {
        $this->createTableCounties();
        $result = $this->mysqli->query("SELECT capital FROM counties");
        $row = $result->fetch_array(MYSQLI_NUM);
        $errors = [];
        $isFirst = true;
        if (is_null($row[0])) {
            foreach ($data as $county) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }
                if (isset($county[0]) && isset($county[1]) && isset($county[2]) && isset($county[3]) && isset($county[4])) {
                    $insert = $this->mysqli->query("UPDATE counties SET capital = '$county[2]', population = '$county[1]', crest = '$county[3]', flag = '$county[4]' WHERE county = '$county[0]'");
                    if (!$insert) {
                        $errors[] = $county[2];
                    }

                }
            }
        }

        return $errors;
    }

    public function get(string $county): array
    {
        $query = "SELECT * FROM counties WHERE county = $county";

        return $this->mysqli->query($query)->fetch_assoc();
    }

    public function getAllCounties()
    {
        $query = "SELECT * FROM counties";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);

    }

    public function getCountyById($id)
    {
        $query = "SELECT county FROM counties WHERE id = $id";

        return $this->mysqli->query($query)->fetch_assoc();
    }

    // public function abcLetters(array $abc)
    // {
    //     for($i = 0; $i< count($abc); i+1) {
    //     {
    //         wd;
    //     }
    // }

    public function displayTable()
    {
        $data = $this->getAllCounties();

        $result = "
        <table>
        <tbody>
        <tr>
                <td>Vármegye</td>
                <td>Megyeszékhely</td>
                <td>Megye lakossága</td>
                <td>Megye címere</td>
                <td>
                Városok
                </td>
            </tr>";
        foreach ($data as $sor) {
            $result .= "
            <tr>
                <td>{$sor['county']}</td>
                <td>{$sor['capital']}</td>
                <td>{$sor['population']}</td>
                <td><img src='{$sor['crest']}' alt=':)'></td>
                <td>
                <button onclick='citiesDisp(\"{$sor['county']}\")'>Városok</button>
                </td>
                
            </tr>
            <label for='{$sor['county']}Id'>
                <tr id='{$sor['county']}Id'>
                    <td colspan='5' style='display:  none'></td>
                </tr>
            </label>
            <label for='{$sor['county']}IdC'>
                <tr id='{$sor['county']}IdC'>
                    <td colspan='5' style='display:  none'></td>
                </tr>
            </label>";
        }
        $result .= "</table>
        </tbody'>";

        return $result;
    }


}