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
require "php/book_details/book_details.php";



$book_details = new Template("html/index.html");

$book_details->setContent("header",$header_page->get());
$book_details->setContent("footer", $footer_page->get());
$book_details->setContent("body",$body_page->get());


$book_details->close();

?>