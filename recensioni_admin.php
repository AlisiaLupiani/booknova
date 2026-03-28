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
require "php/recensioni_admin/recensioni_admin.php";





$recensioni = new Template("html/index_admin/index_admin.html");

$recensioni->setContent("header",$header_page->get());
$recensioni->setContent("footer", $footer_page->get());
$recensioni->setContent("body",$body_page->get());


$recensioni->close();

?>