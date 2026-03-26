<?php


if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"visualizza_ordine\".php");
    exit;
}



$body_page = new Template("html/visualizza_ordini/visualizza_ordini.html");

?>