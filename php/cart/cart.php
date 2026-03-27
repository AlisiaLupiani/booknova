<?php

if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"cart\".php");
    exit;
}
$body_page = new Template("html/cart/cart.html");

?>