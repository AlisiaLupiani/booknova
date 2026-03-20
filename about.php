<?php 
# session_start();

// Templating
require_once("include/template2.inc.php");

// Database
# require_once("include/db/DB_Connection.php");
# require_once("include/db/DataLayer.php");

// Page
require "php/home/header.php";
require "php/home/footer.php";
require "php/about/about.php";



$about = new Template("html/index.html");

$about->setContent("header",$header_page->get());
$about->setContent("footer", $footer_page->get());
$about->setContent("body",$body_page->get());


$about->close();

?>