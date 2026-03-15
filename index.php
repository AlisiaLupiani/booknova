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
require "php/home/body.php";



$homepage = new Template("html/index.html");

$homepage->setContent("header",$header_page->get());
$homepage->setContent("footer", $footer_page->get());
$homepage->setContent("body",$body_page->get());


$homepage->close();

?>