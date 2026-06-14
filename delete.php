<?php
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

if (!isset($_GET["book_id"]) || !is_numeric($_GET["book_id"])) {
    die("ID libro non valido");
}

$book_id = (int)$_GET["book_id"];

$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO();

if ($bookDAO->deleteBook($book_id)) {
    header("Location: visualizza_libri.php?deleted=1");
    exit;
} else {
    die("Errore durante l'eliminazione del libro");
}
