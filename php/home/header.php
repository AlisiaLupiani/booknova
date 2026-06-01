<?php
// Questo è il file: php/home/header.php

// Scegli il template dell'header in base allo stato di autenticazione
$templateFile = (isset($_SESSION["auth"]) && $_SESSION["auth"] === true) ? "html/home/header.html" : "html/home/header_guest.html";
$header_page = new Template($templateFile); // Verifica che il percorso del file HTML sia corretto!

// Controlliamo lo stato del login (la sessione è già attiva grazie a index.php)
if (isset($_SESSION["auth"]) && $_SESSION["auth"] === true) {
    // 1. Se l'utente è loggato, mostra Logout
    $login_button = '<a href="logout.php" class="nav-link">Logout (' . $_SESSION["name"] . ')</a>';
    
    // 2. Passiamo l'ID dell'utente al segnaposto dell'header HTML per i link (Wishlist, Account, Cart)
    $header_page->setContent("user_id", $_SESSION["id"]);
    // Compute cart count from DB
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
    // Se l'utente NON è loggato, mostra Accedi
    $login_button = '<a href="login.php" class="nav-link">Accedi</a>';
    // Se non è loggato usando il template guest non ci sono altri segnaposti necessari,
    // ma impostiamo comunque valori di default per evitare placeholder non sostituiti.
    $header_page->setContent("user_id", "");
    $header_page->setContent("cart_count", 0);
}

// Inietta il bottone nel segnaposto dell'header HTML
$header_page->setContent("auth_button", $login_button);

// Nota: Ricordati che l'header di solito viene stampato o incluso dentro la index.
// Se la index fa un $body_page->setContent("header", $header_page->render()); allora qui sei a posto!