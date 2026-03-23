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
require "php/account/account.php";



$account = new Template("html/index.html");

$account->setContent("header",$header_page->get());
$account->setContent("footer", $footer_page->get());
$account->setContent("body",$body_page->get());


$account->close();

?>