<?php

if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"aggiungi_recensione\".php");
    exit;
}
$body_page = new Template("html/aggiungi_recensione/aggiungi_recensione.html");

?>