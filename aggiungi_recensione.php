<?php 
session_start();

// Templating
require_once("include/template2.inc.php");

// Database
# require_once("include/db/DB_Connection.php");
# require_once("include/db/DataLayer.php");

// Page
require "php/home/header.php";
require "php/home/footer.php";
require "php/aggiungi_recensione/aggiungi_recensione.php";



$recensione = new Template("html/index.html");

$recensione->setContent("header",$header_page->get());
$recensione->setContent("footer", $footer_page->get());
$recensione->setContent("body",$body_page->get());


$recensione->close();

?>