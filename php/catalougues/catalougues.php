<?php

// Utility
require_once("include/utility/QueryStringBuilder.php");


$body_page = new Template("html/catalougues/catalougues.html");

$dataLayer = new DataLayer(new DB_Connection());

$categoryDAO = $dataLayer->getCategoryDAO();
$bookDAO = $dataLayer->getBookDAO();
$categories = $categoryDAO->getAllCategories();
$books = $bookDAO->getAllBooks();



# Carica tutte le categorie
foreach ($categories as $category) {
    $body_page->setContent("category", str_replace(' ', '_', $category->getName()));
    $body_page->setContent("categorylabel", $category->getName());
}

# Prende tutti i libri
foreach ($books as $book) {
      
        $body_page->setContent("bookimageallgenre", "static/img/" . $book->getImagePath());
        $body_page->setContent("booktitleallgenre", $book->getTitle());
        $body_page->setContent("authorallgenre", $book->getAuthor()->getName());
        $body_page->setContent("priceallgenre", $book->getPrice());
        $body_page->setContent("bookidallgenre", $book->getId());   
        $string_builder = new QueryStringBuilder("book_details.php");
        $string_builder->add("book_id", $book->getId());

        $body_page->setContent("bookallgenrehrefid", $string_builder->build());


       
}
 
# Prende i libri in base alla categoria
foreach ($categories as $category) {
    $body_page->setContent("categorytab", str_replace(' ', '_', $category->getName()));
    $books_by_category = $bookDAO->getBooksByCategory($category->getId()); 
    
    foreach ($books_by_category as $book) {
        $body_page->setContent("bookimagine", "static/img/" . $book->getImagePath());
        $body_page->setContent("booktitlecategory", $book->getTitle());
        $body_page->setContent("authorcategory", $book->getAuthor()->getName());
        $body_page->setContent("pricecategory", $book->getPrice());
         $body_page->setContent("bookidallcategory", $book->getId()); 
    }

    
}
