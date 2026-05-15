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
require "php/libri_offerta/libri_offerta.php";



$libri = new Template("html/index.html");

$libri->setContent("header",$header_page->get());
$libri->setContent("footer", $footer_page->get());
$libri->setContent("body",$body_page->get());


$libri->close();

?>