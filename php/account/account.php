<?php

if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"account\".php");
    exit;
}
$body_page = new Template("html/account/account.html");

?>