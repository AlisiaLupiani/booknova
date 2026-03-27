<?php

if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"indirizzo\".php");
    exit;
}
$body_page = new Template("html/indirizzo/indirizzo.html");

?>