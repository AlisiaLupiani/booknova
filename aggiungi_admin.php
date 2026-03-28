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
require "php/aggiungi_admin/aggiungi_admin.php";



$aggiungi_admin = new Template("html/index_admin/index_admin.html");

$aggiungi_admin->setContent("header",$header_page->get());
$aggiungi_admin->setContent("footer", $footer_page->get());
$aggiungi_admin->setContent("body",$body_page->get());


$aggiungi_admin->close();

?>