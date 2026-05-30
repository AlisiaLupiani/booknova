<?php

// 1. Avvia la sessione
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/QueryStringBuilder.php");

// 2. Controllo login
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $ref_encoded = base64_encode("wishlist.php");
    header("Location: login.php?reference=" . $ref_encoded);
    exit;
}

// 3. Controllo user_id
if (!isset($_GET["user_id"]) || !is_numeric($_GET["user_id"])) {
    echo "Errore: Utente non trovato o ID non valido.";
    exit(); 
}

// 4. Template e DataLayer
$body_page = new Template("html/wishlist/wishlist.html");
$dataLayer = new DataLayer(new DB_Connection());

// 5. Recupero dati
$user_id = (int)$_GET["user_id"];

$UserDAO = $dataLayer->getUserDAO();
$user = $UserDAO->getUserById($user_id);

$wishlistDAO = $dataLayer->getWishlistDAO();
$wishlist = $wishlistDAO->getWishlistByUser($user_id);

// 6. Popolamento template
foreach ($wishlist as $wishlist_item) {

    $libro = $wishlist_item->getBook(); 

    // Dati libro
    $body_page->setContent("bookimageallgenre", "static/img/" . $libro->getImagePath());
    $body_page->setContent("booktitleallgenre", $libro->getTitle());
    $body_page->setContent("authorallgenre", $libro->getAuthor()->getName());
    $body_page->setContent("priceallgenre", $libro->getPrice());

    // ID libro → NECESSARIO per il REMOVE
    $body_page->setContent("bookidallgenre", $libro->getId());

    // Link ai dettagli
    $string_builder = new QueryStringBuilder("book_details.php");
    $string_builder->add("book_id", $libro->getId());
    $body_page->setContent("bookallgenrehrefid", $string_builder->build());
}



?>
