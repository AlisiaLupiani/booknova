<?php 
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => false, // Metti true se usi HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once("include/template2.inc.php");
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/QueryStringBuilder.php");


$dataLayer = new DataLayer(new DB_Connection());


require "php/home/header.php";
require "php/home/footer.php";
require "php/home/body.php"; 


$homepage = new Template("html/index.html");
$homepage->setContent("header", $header_page->get());
$homepage->setContent("footer", $footer_page->get());
$homepage->setContent("body", $body_page->get());

$homepage->close();
?>