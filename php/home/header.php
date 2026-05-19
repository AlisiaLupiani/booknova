<?php
// Questo è il file: php/home/header.php

// Carica il template dell'header HTML
$header_page = new Template("html/home/header.html"); // Verifica che il percorso del file HTML sia corretto!

// Controlliamo lo stato del login (la sessione è già attiva grazie a index.php)
if (isset($_SESSION["auth"]) && $_SESSION["auth"] === true) {
    // 1. Se l'utente è loggato, mostra Logout
    $login_button = '<a href="logout.php" class="nav-link">Logout (' . $_SESSION["name"] . ')</a>';
    
    // 2. Passiamo l'ID dell'utente al segnaposto dell'header HTML per i link (Wishlist, Account, Cart)
    $header_page->setContent("user_id", $_SESSION["id"]);
} else {
    // Se l'utente NON è loggato, mostra Accedi
    $login_button = '<a href="login.php" class="nav-link">Accedi</a>';
    
    // Se non è loggato, svuotiamo il tag nell'HTML (o lo lasciamo vuoto per non rompere i link)
    $header_page->setContent("user_id", "");
}

// Inietta il bottone nel segnaposto dell'header HTML
$header_page->setContent("auth_button", $login_button);

// Nota: Ricordati che l'header di solito viene stampato o incluso dentro la index.
// Se la index fa un $body_page->setContent("header", $header_page->render()); allora qui sei a posto!