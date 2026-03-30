<?php 
session_start();

// Templating
require_once("include/template2.inc.php");

// Database
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

// Page
require "php/home/header.php";
require "php/home/footer.php";
require "php/cart/cart.php";



$cart = new Template("html/index.html");

$cart->setContent("header",$header_page->get());
$cart->setContent("footer", $footer_page->get());
$cart->setContent("body",$body_page->get());


$cart->close();

?>