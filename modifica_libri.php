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
require "php/modifica_libri/modifica_libri.php";



$modifica_libri = new Template("html/index.html");

$modifica_libri->setContent("header",$header_page->get());
$modifica_libri->setContent("footer", $footer_page->get());
$modifica_libri->setContent("body",$body_page->get());


$modifica_libri->close();

?>