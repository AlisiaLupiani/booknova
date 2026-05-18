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
require "php/details_order/details_order.php";



$details_order = new Template("html/index.html");

$details_order->setContent("header",$header_page->get());
$details_order->setContent("footer", $footer_page->get());
$details_order->setContent("body",$body_page->get());


$details_order->close();

?>