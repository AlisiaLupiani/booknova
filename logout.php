<?php 
session_start();

require_once("include/template2.inc.php");
require_once("include/utility/AuthManager.php");

// Database
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

// Page
require "php/home/header.php";
require "php/home/footer.php";
require "php/logout/logout.php";



$logout = new Template("html/index.html");

$logout->setContent("header",$header_page->get());
$logout->setContent("footer", $footer_page->get());
$logout->setContent("body",$body_page->get());


$logout->close();

?>