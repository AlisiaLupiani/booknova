<?php

$body_page = new Template("html/book_details/book_details.html");


// DAO
$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO();

// Controlla se è stato passato effettivamente un id
if(!isset($_GET["book_id"])){
    // Visualizzi un messaggio di errore
}

// Recupero il libro da DB
$book_id = $_GET["book_id"];
$book = $bookDAO->getBookById($book_id);


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