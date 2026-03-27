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
require "php/wishlist/wishlist.php";




$wishlist = new Template("html/index.html");

$wishlist->setContent("header",$header_page->get());
$wishlist->setContent("footer", $footer_page->get());
$wishlist->setContent("body",$body_page->get());


$wishlist->close();

?>