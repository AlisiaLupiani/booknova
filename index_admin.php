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
require "php/home_admin/body_admin.php";



$homeadmin = new Template("html/index.html");

$homeadmin->setContent("header",$header_page->get());
$homeadmin->setContent("footer", $footer_page->get());
$homeadmin->setContent("body",$body_page->get());


$homeadmin->close();

?>