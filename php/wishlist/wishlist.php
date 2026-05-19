<?php

// 1. Avvia la sessione prima di fare qualsiasi controllo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/QueryStringBuilder.php");

// 2. CONTROLLO DI SICUREZZA: L'utente è loggato? Se no, fermati subito e vai al login
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $ref_encoded = base64_encode("wishlist.php");
    header("Location: login.php?reference=" . $ref_encoded);
    exit;
}

// 3. CONTROLLO DELL'URL: C'è un user_id valido nell'URL? Se no, fermati e mostra l'errore
if (!isset($_GET["user_id"]) || !is_numeric($_GET["user_id"])) {
    echo "Errore: Utente non trovato o ID non valido.";
    exit(); 
}

// 4. Se siamo arrivati qui, è tutto sicuro! Inizializziamo il template e i dati
$body_page = new Template("html/wishlist/wishlist.html");
$dataLayer = new DataLayer(new DB_Connection());

// 5. Recuperiamo l'ID in modo sicuro e facciamo le query
$user_id = (int)$_GET["user_id"];

$UserDAO = $dataLayer->getUserDAO();
$user = $UserDAO->getUserById($user_id);

$whishlistDAO = $dataLayer->getWishlistDAO();
$whishlist = $whishlistDAO->getWishlistByUser($user_id);


// 6. Ciclo per popolare il template
foreach ($whishlist as $wishlist_item) {
$libro = $wishlist_item->getBook(); 

        // Ora prendiamo i dati direttamente dall'oggetto $libro!
        $body_page->setContent("bookimageallgenre", "static/img/" . $libro->getImagePath());
        $body_page->setContent("booktitleallgenre", $libro->getTitle());
        $body_page->setContent("authorallgenre", $libro->getAuthor()->getName());
        $body_page->setContent("priceallgenre", $libro->getPrice());

        $string_builder = new QueryStringBuilder("book_details.php");
        $string_builder->add("book_id", $libro->getId());

        $body_page->setContent("bookallgenrehrefid", $string_builder->build());
}

// Ricordati di fare l'output del template se non lo fai altrove!
// echo $body_page->render();
?>