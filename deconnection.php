<?php
session_start();

$_SESSION = array();
session_destroy();

if (isset($_COOKIE['email']) && isset($_COOKIE['pass'])) {
    setcookie('email', '');
    setcookie('pass', '');
}
header('Location:connection.php');
?>