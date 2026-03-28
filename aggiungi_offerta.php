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
require "php/aggiungi_offerta/aggiungi_offerta.php";



$aggiungi_offerta = new Template("html/index_admin/index_admin.html");

$aggiungi_offerta->setContent("header",$header_page->get());
$aggiungi_offerta->setContent("footer", $footer_page->get());
$aggiungi_offerta->setContent("body",$body_page->get());


$aggiungi_offerta->close();

?>