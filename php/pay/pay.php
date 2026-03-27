<?php

if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"pay\".php");
    exit;
}
$body_page = new Template("html/pay/pay.html");

?>