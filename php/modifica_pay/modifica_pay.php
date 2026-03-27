<?php

if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"modifica_pay\".php");
    exit;
}
$body_page = new Template("html/modifica_pay/modifica_pay.html");

?>