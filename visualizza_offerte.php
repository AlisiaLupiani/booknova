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
require "php/visualizza_offerte/visualizza_offerte.php";



$visualizza_offerte = new Template("html/index_admin/index_admin.html");

$visualizza_offerte->setContent("header",$header_page->get());
$visualizza_offerte->setContent("footer", $footer_page->get());
$visualizza_offerte->setContent("body",$body_page->get());


$visualizza_offerte->close();

?>