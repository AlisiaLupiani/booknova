<?php
// 1. Avvia la sessione per poterla leggere e distruggere
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Svuota completamente l'array delle sessioni
$_SESSION = array();

// 3. Cancella il cookie di sessione dal browser dell'utente
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 4. Distruggi la sessione sul server
session_destroy();

// 5. Forza il browser a non usare la cache e reindirizza alla home
header("Cache-Control: no-cache, must-revalidate, max-age=0");
header("Location: index.php");
exit;