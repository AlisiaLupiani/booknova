<?php
// php/home/body.php

// 1. Crea l'istanza del template per il corpo
$body_page = new Template("html/home/body.html"); 

// 2. Recupera i dati (Assumiamo che $dataLayer sia globale o già creato in index)
global $dataLayer; 

$bookDAO = $dataLayer->getBookDAO();
$randomBook[] = $bookDAO->getRandomBook();
$libri = $bookDAO->getRandomBook(4); 
$data = [];
foreach($libri as $b) {
    $data[] = ["random_title" => $b->getTitle(), "random__price" => $b->getPrice(), "random_author" => $b->getAuthor() ? $b->getAuthor()->getName() : "Autore Sconosciuto", "random_description" => $b->getDescription(), "random_id" => $b->getId()];
    $body_page->setContent("random_title", $b->getTitle());
    $body_page->setContent("random__price", $b->getPrice());
    $author = $b->getAuthor();
    $body_page->setContent("random_author", $author ? $author->getName() : "Autore Sconosciuto");
    $body_page->setContent("random_description", $b->getDescription());
    $body_page->setContent("random_id", $b->getId());
}
$body_page->setContent("libri_random", $data);

$libri = $bookDAO->getRandomBook(1); 
$randomBook = $libri[0];
if ($randomBook) {
    $body_page->setContent("random_book_title", $randomBook->getTitle());
    $body_page->setContent("random_book_price", $randomBook->getPrice());
    $author = $randomBook->getAuthor();
    $body_page->setContent("random_book_author", $author ? $author->getName() : "Autore Sconosciuto");
    
    $body_page->setContent("random_book_description", $randomBook->getDescription());
    $body_page->setContent("random_book_id", $randomBook->getId());
}
?>