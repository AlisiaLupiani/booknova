<?php

require_once("include/utility/QueryStringBuilder.php");
$body_page = new Template("html/visualizza_libri/visualizza_libri.html");

$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO();
$books = $bookDAO->getAllBooks();


# Prende tutti i libri
foreach ($books as $book) {
      
       $body_page->setContent("book_id", $book->getId());
        $body_page->setContent("booktitle", $book->getTitle());
        $body_page->setContent("author", $book->getAuthor()->getName());
        $body_page->setContent("price", $book->getPrice());
        $body_page->setContent("category", $book-> getCategory()->getName());
        $body_page->setContent("publisher", $book->getPublisher()->getName());
        $body_page->setContent("pages", $book->getPages());


        $string_builder = new QueryStringBuilder("modifica_libro.php");
        $string_builder->add("book_id", $book->getId());

        $body_page->setContent("bookallgenrehrefid", $string_builder->build());


}