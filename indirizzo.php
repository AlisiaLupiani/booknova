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
require "php/indirizzo/indirizzo.php";



$indirizzo = new Template("html/index.html");

$indirizzo->setContent("header",$header_page->get());
$indirizzo->setContent("footer", $footer_page->get());
$indirizzo->setContent("body",$body_page->get());


$indirizzo->close();

?>