<?php
require_once("login_if.php");
require_once("registration.php");
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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
      $this->mysqli->query($sql);
      $mail = new PHPMailer(true);

      try {
        //Server settings
        $mail->isSMTP();                                            
        $mail->Host       = 'localhost';                     
        $mail->SMTPAuth   = false;                                                                 
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
        $mail->Port       = 1025;                                    

        //Recipients
        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress($email, $username);     
        $mail->addReplyTo('info@example.com', 'Information'); 

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Conformation letter';
        $mail->Body    = "Your registration is almost complete <br> Click the link below to activate your account: <br> <a href='http://localhost:8084/Demény%20Máté%20PHP/login/registration.php?token=$token'>Complete registration</a>";
        $mail->AltBody = '';

        $mail->send();
        echo "An email has been sent to you with the remaining steps.";
      } 
      catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    }



    function login($email, $password)
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
        
      }
    }

    function registrationSucc($email)
    {
      $date = new DateTime();
      $date = $date->format("Y-m-d H:i:s");
      $sql = "UPDATE login_table SET is_active = TRUE, registered_at = '$date' WHERE email = '$email'";
      echo "Registration complete!";
      return $this->mysqli->query($sql);
    }

    function getUserByToken($token)
    {
      $sql = "SELECT * FROM login_table WHERE token = '$token' AND token_valid_until > NOW()";
      $result = $this->mysqli->query($sql);
      $fetch = $result->fetch_array(MYSQLI_NUM);
      return $fetch;
    }

    function emailExists($email)
    {
      $sql = "SELECT email FROM login_table WHERE email = '$email'";
      $result = $this->mysqli->query($sql);
      $fetch = $result->fetch_array(MYSQLI_NUM);
      
      return empty($fetch);
    }

    
    
}

