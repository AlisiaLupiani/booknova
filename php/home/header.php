<?php

$templateFile = (isset($_SESSION["auth"]) && $_SESSION["auth"] === true) ? "html/home/header.html" : "html/home/header_guest.html";
$header_page = new Template($templateFile); // Verifica che il percorso del file HTML sia corretto!

if (isset($_SESSION["auth"]) && $_SESSION["auth"] === true) {
    $login_button = '<a href="logout.php" class="nav-link">Logout (' . $_SESSION["name"] . ')</a>';
    
    $header_page->setContent("user_id", $_SESSION["id"]);
    require_once __DIR__ . '/../../include/db/DB_Connection.php';
    require_once __DIR__ . '/../../include/db/DataLayer.php';
    try {
        $dl = new DataLayer(new DB_Connection());
        $cartDAO = $dl->getCartDAO();
        $items = $cartDAO->getCartItemsByUserId((int)$_SESSION["id"]);
        $cart_count = is_array($items) ? count($items) : 0;
    } catch (Exception $e) {
        $cart_count = 0;
    }
    $header_page->setContent("cart_count", $cart_count);
} else {
    $login_button = '<a href="login.php" class="nav-link">Accedi</a>';
    $header_page->setContent("user_id", "");
    $header_page->setContent("cart_count", 0);
}

$header_page->setContent("auth_button", $login_button);

