<?php

class installDB
{
    protected $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null)
    {
        $this->mysqli = mysqli_connect($host, $user, $password, 'mysql');
        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
          }
    }
    function createDatabase(){
        $this->mysqli = mysqli_connect('localhost', 'root', null);
  
        if($this->mysqli->connect_error)
        {
        die("Connection failed: " . $this->mysqli->connect_error);
        }
        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS login_db";
        if (!$this->mysqli->query($sql) === TRUE) 
        {
        echo "Error creating database: " . $this->mysqli->error;
        }
    }

      function __destruct()
      {
          $this->mysqli->close();
      }
}