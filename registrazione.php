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
require "php/registrazione/registrazione.php";



$registrazione = new Template("html/index.html");

$registrazione->setContent("header",$header_page->get());
$registrazione->setContent("footer", $footer_page->get());
$registrazione->setContent("body",$body_page->get());


$registrazione->close();

?>