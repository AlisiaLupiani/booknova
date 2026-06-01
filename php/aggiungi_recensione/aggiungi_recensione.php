<?php
// Pagina di visualizzazione (solo GET)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"aggiungi_recensione\".php");
    exit;
}

$body_page = new Template("html/aggiungi_recensione/aggiungi_recensione.html");

// Imposta valori precompilati nel template
$body_page->setContent('book_id', isset($_GET['book_id']) ? (int)$_GET['book_id'] : '');
$body_page->setContent('user_name', isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : '');
$body_page->setContent('messaggio', '');

?>