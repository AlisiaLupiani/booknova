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
require "php/visualizza_ordini/visualizza_ordine.php";



$visualizza_ordine  = new Template("html/index.html");

$visualizza_ordine ->setContent("header",$header_page->get());
$visualizza_ordine ->setContent("footer", $footer_page->get());
$visualizza_ordine ->setContent("body",$body_page->get());


$visualizza_ordine ->close();

?>