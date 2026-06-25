<?php

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/proxy/PermissionProxy.php");
require_once("include/template2.inc.php");
require_once("include/utility/QueryStringBuilder.php");

// 🔥 CREA IL DATALAYER
$factory = new DataLayer(new DB_Connection());

// 🔥 CREA IL PERMISSION PROXY
$permission = new PermissionProxy($factory);

// 🔥 CONTROLLA IL PERMESSO
$permission->checkPermission("book_view");

// DAO
$bookDAO = $factory->getBookDAO();
$books = $bookDAO->getAllBooks();

// Template
$body_page = new Template("html/visualizza_libri/visualizza_libri.html");

// Loop libri
foreach ($books as $book) {

    $body_page->setContent("book_id", $book->getId());
    $body_page->setContent("booktitle", $book->getTitle());
    $body_page->setContent("author", $book->getAuthor()->getName());
    $body_page->setContent("price", $book->getPrice());
    $body_page->setContent("category", $book->getCategory()->getName());
    $body_page->setContent("publisher", $book->getPublisher()->getName());
    $body_page->setContent("pages", $book->getPages());

    $string_builder = new QueryStringBuilder("modifica_libro.php");
    $string_builder->add("book_id", $book->getId());

    $body_page->setContent("bookallgenrehrefid", $string_builder->build());
}
?>
