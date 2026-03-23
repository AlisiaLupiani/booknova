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
require "php/pay/pay.php";



$pay = new Template("html/index.html");

$pay->setContent("header",$header_page->get());
$pay->setContent("footer", $footer_page->get());
$pay->setContent("body",$body_page->get());


$pay->close();

?>