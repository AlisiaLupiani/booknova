<?php

if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"ordini\".php");
    exit;
}
$body_page = new Template("html/ordini/ordini.html");

?>