<?php
require_once("login_if.php");
class Login
{
    protected $mysqli;

    
    function __construct($host = 'localhost', $user = 'root', $password = null)
    {
        $this->mysqli = mysqli_connect($host, $user, $password, 'login_db');
        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
          }
    }

    function __destruct()
    {
        $this->mysqli->close();
    }

    function createLoginTable()
    {
      $query = 'CREATE TABLE IF NOT EXISTS login_table(
      id int PRIMARY KEY AUTO_INCREMENT,
      is_active tinyint default false,
      name varchar(50) not null,
      email varchar(25) not null unique,
      password varchar(50) not null,
      token varchar(100),
      token_valid_until datetime, 
      created_at datetime default now(),
      registered_at datetime,
      picture varchar(50),
      deleted_at datetime)';
      return $this->mysqli->query($query);
    }

    function token($data = null) {
      $data = $data ?? random_bytes(16);
      assert(strlen($data) == 16);
      $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
      $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
      return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }

    function registerPush($username, $email, $password)
    {
      $validUntil = new DateTime();
      $validUntil->add(new DateInterval('PT2H'));
      $validUntil = $validUntil->format("Y-m-d H:i:s");
      $token = $this->token();
      $sql = "INSERT INTO login_table(name, email, password, token, token_valid_until)
      VALUES('$username', '$email', '$password', '$token', '$validUntil')";
      return $this->mysqli->query($sql);
    }

    function loginPush($email, $password)
    {
      $sql = "SELECT * FROM login_table WHERE email = '$email' AND password = '$password'";
      $result = $this->mysqli->query($sql);
      $fetch = $result->fetch_array(MYSQLI_NUM);
      if(empty($fetch))
      {
        echo '<script>alert("Helytelen bejelentkezési adatok!")</script>';
      }
      else
      {
        $date = new DateTime();
        $date = $date->format("Y-m-d H:i:s");
        $sql = "UPDATE login_table SET is_active = TRUE, registered_at = '$date' WHERE email = '$email'";
        return $this->mysqli->query($sql);
      }
    }

    function emailExists($email)
    {
      $sql = "SELECT email FROM login_table WHERE email = '$email'";
      $result = $this->mysqli->query($sql);
      $fetch = $result->fetch_array(MYSQLI_NUM);
      
      return empty($fetch);
    }
    
}

