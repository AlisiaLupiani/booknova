<?php
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

header("Content-Type: application/json");

$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO();

if (!isset($_POST["book_id"])) {
    echo json_encode(["success" => false, "message" => "ID mancante"]);
    exit;
}

$book = $bookDAO->getBookById($_POST["book_id"]);

if (!$book) {
    echo json_encode(["success" => false, "message" => "Libro non trovato"]);
    exit;
}



// PREZZO
$book->setPrice($_POST["bookPrice"]);


$updated = $bookDAO->storeBook($book);

echo json_encode([
    "success" => $updated ? true : false,
    "message" => $updated ? "Libro aggiornato con successo!" : "Errore durante l'aggiornamento."
]);
