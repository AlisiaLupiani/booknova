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
require "php/modifica_pay/modifica_pay.php";



$modifica_pay = new Template("html/index.html");

$modifica_pay->setContent("header",$header_page->get());
$modifica_pay->setContent("footer", $footer_page->get());
$modifica_pay->setContent("body",$body_page->get());


$modifica_pay->close();

?>
