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
require "php/modifica_offerta/modifica_offerta.php";



$modifica_offerta = new Template("html/index.html");

$modifica_offerta->setContent("header",$header_page->get());
$modifica_offerta->setContent("footer", $footer_page->get());
$modifica_offerta->setContent("body",$body_page->get());


$modifica_offerta->close();

?>