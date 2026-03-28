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
require "php/aggiungi_categoria/aggiungi_categoria.php";



$aggiungi = new Template("html/index_admin/index_admin.html");

$aggiungi->setContent("header",$header_page->get());
$aggiungi->setContent("footer", $footer_page->get());
$aggiungi->setContent("body",$body_page->get());


$aggiungi->close();

?>