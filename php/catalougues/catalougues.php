<?php

$body_page = new Template("html/catalougues/catalougues.html");

$dataLayer = new DataLayer(new DB_Connection());
$categoryDAO = $dataLayer->getCategoryDAO();
$bookDAO = $dataLayer->getBookDAO();
$categories = $categoryDAO->getAllCategories();

foreach ($categories as $category) {
    $body_page->setContent("category", $category->getName());
}
foreach ($categories as $category) {
    $body_page->setContent("categorytab", $category->getName());
    foreach ($bookDAO->getBooksByCategory($category->getId()) as $book) {
        $body_page->setContent("booktitle", $book->getTitle());
        $body_page->setContent("autore", $book->getAuthor()->getName());
        $body_page->setContent("price", $book->getPrice());
    }
}
