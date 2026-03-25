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
require "php/password/password.php";



$password = new Template("html/index.html");

$password->setContent("header",$header_page->get());
$password->setContent("footer", $footer_page->get());
$password->setContent("body",$body_page->get());


$password->close();

?>