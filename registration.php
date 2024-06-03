<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>
    <?php
    if(!empty($_GET['token']))
    {
        require_once("login.php");
        $login = new Login();
        $fetch = $login->getUserByToken($_GET['token']);
        if(!empty($fetch))
        {
            $email = $fetch[3];
            $login->registrationSucc($email);
        }
        else
        {
            echo "Lejárt a token! Kattintson a linkre: ";
        }
    }
    
      
    ?>
</body>
</html>