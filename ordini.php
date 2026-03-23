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
require "php/ordini/ordini.php";



$ordini = new Template("html/index.html");

$ordini->setContent("header",$header_page->get());
$ordini->setContent("footer", $footer_page->get());
$ordini->setContent("body",$body_page->get());


$ordini->close();

?>