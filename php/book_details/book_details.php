<?php

$body_page = new Template("html/book_details/book_details.html");

// DAO
$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO();

// 1. Controlla se è stato passato effettivamente un id
if (!isset($_GET["book_id"]) || !is_numeric($_GET["book_id"])) {

    echo "Errore: Libro non trovato o ID non valido.";
    exit(); 
}


$book_id = (int)$_GET["book_id"];
$book = $bookDAO->getBookById($book_id);


if (!$book) {
    echo "Errore: Il libro richiesto non esiste nel nostro database.";
    exit();
}


$body_page->setContent("book_image", "static/img/" . $book->getImagePath());
$body_page->setContent("booktitle", $book->getTitle());
$body_page->setContent("author", $book->getAuthor()->getName());
$body_page->setContent("description", $book->getDescription());
$body_page->setContent("price", $book->getPrice());
$body_page->setContent("pages", $book->getPages());
$body_page->setContent("condition", $book->getCondition()->getCondition());
$body_page->setContent("publisher", $book->getPublisher()->getName());
$body_page->setContent("publication_year", $book->getPublicationYear());

$body_page->setContent("btnbookid", $book->getId());

?>