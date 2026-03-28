<?php 
session_start();

// Templating
require_once("include/template2.inc.php");

// Database
# require_once("include/db/DB_Connection.php");
# require_once("include/db/DataLayer.php");

// Page
require "php/home_admin/header_admin.php";
require "php/home_admin/footer_admin.php";
require "php/visualizza_utenti/visualizza_utenti.php";



$visualizza_utenti = new Template("html/index_admin/index_admin.html");

$visualizza_utenti->setContent("header",$header_page->get());
$visualizza_utenti->setContent("footer", $footer_page->get());
$visualizza_utenti->setContent("body",$body_page->get());


$visualizza_utenti->close();

?>