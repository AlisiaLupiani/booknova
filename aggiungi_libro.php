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
require "php/aggiungi_libro/aggiungi_libro.php";



$aggiungi_libro = new Template("html/index_admin/index_admin.html");

$aggiungi_libro->setContent("header",$header_page->get());
$aggiungi_libro->setContent("footer", $footer_page->get());
$aggiungi_libro->setContent("body",$body_page->get());


$aggiungi_libro->close();

?>