<?php 
session_start();

// Templating
require_once("include/template2.inc.php");

// Database
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

// Page
require "php/home_admin/header_admin.php";
require "php/home_admin/footer_admin.php";
require "php/permessi_admin/permessi_admin.php";




$permessi = new Template("html/index_admin/index_admin.html");

$permessi->setContent("header",$header_page->get());
$permessi->setContent("footer", $footer_page->get());
$permessi->setContent("body",$body_page->get());


$permessi->close();

?>