<?php
require_once("index.php");
require_once("login.php");
$login = new Login;

function showRegisterInterFace()
{
    echo"
    <form method='post'>
        <div class='name'>
            <input type='text' id='name' name='name' placeholder='Felhasználónév' required>
        </div>
        <div class='email'>
            <input type='email' id='email' name='email' placeholder='Email' required>
        </div>
        <div class='password'>
            <input type='password' id='password1' name='password1' placeholder='Jelszó' required>
        </div>
        <div class='password'>
            <input type='password' name='password2' placeholder='Jelszó újra' required>
        </div>
        <input id='btn-register-push' name='btn-register-push' type='submit' value='Register'></input>
    </form>
    ";
}

function ShowLoginInterface()
{
    echo"
    <form method='post'>
        <div class='email'>
            <input type='email' id='email' name='email' placeholder='Email' required>
        </div>
        <div class='password'>
            <input type='password' id='password1' name='password1' placeholder='Jelszó' required>
        </div>
        <input id='btn-login-push' name='btn-login-push' type='submit' value='Log in'></input>
    </form>
    ";
}