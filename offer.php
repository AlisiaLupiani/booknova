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
require "php/offer/offer.php";



$offer = new Template("html/index.html");

$offer->setContent("header",$header_page->get());
$offer->setContent("footer", $footer_page->get());
$offer->setContent("body",$body_page->get());


$offer->close();

?>