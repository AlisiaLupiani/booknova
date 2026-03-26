<?php 
session_start();

// Templating
require_once("include/template2.inc.php");

// Database
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

// Page
require "php/home/header.php";
require "php/home/footer.php";
require "php/login/login.php";



$login = new Template("html/index.html");

$login->setContent("header",$header_page->get());
$login->setContent("footer", $footer_page->get());
$login->setContent("body",$body_page->get());


$login->close();

?>