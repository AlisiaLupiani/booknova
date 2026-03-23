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
require "php/merchandising/merchandising.php";



$merchandising = new Template("html/index.html");

$merchandising->setContent("header",$header_page->get());
$merchandising->setContent("footer", $footer_page->get());
$merchandising->setContent("body",$body_page->get());


$merchandising->close();

?>