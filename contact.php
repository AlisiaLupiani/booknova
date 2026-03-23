<?php 
session_start();

// Templating
require_once("include/template2.inc.php");

// Database
# require_once("include/db/DB_Connection.php");
# require_once("include/db/DataLayer.php");

// Page
require "php/home/header.php";
require "php/home/footer.php";
require "php/contact/contact.php";



$contact = new Template("html/index.html");

$contact->setContent("header",$header_page->get());
$contact->setContent("footer", $footer_page->get());
$contact->setContent("body",$body_page->get());


$contact->close();

?>