<?php

class DB
{
    protected $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null, $database = 'megyek_db')
    {
        $this->mysqli = mysqli_connect($host, $user, $password, $database);
        if (!$this->mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    function __destruct()
    {
        $this->mysqli->close();
    }
}
