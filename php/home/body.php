<?php
// php/home/body.php

// 1. Crea l'istanza del template per il corpo
$body_page = new Template("html/home/body.html");  
global $dataLayer; 

$bookDAO = $dataLayer->getBookDAO();
$randomBook[] = $bookDAO->getRandomBook();
$libri = $bookDAO->getRandomBook(4); 
$data = [];
foreach($libri as $b) {
    
    $data[] = ["random_title" => $b->getTitle(), "random__price" => $b->getPrice(), "random_author" => $b->getAuthor() ? $b->getAuthor()->getName() : "Autore Sconosciuto", "random_description" => $b->getDescription(), "random_id" => $b->getId(), "random_image" => "static/img/" . $b->getImagePath()];
    $body_page->setContent("random_title", $b->getTitle());
    $body_page->setContent("random__price", $b->getPrice());
    $author = $b->getAuthor();
    $body_page->setContent("random_author", $author ? $author->getName() : "Autore Sconosciuto");
    $body_page->setContent("random_description", $b->getDescription());
    $body_page->setContent("random_id", $b->getId());
    $body_page->setContent("random_image", "static/img/" . $b->getImagePath());
   
}
$body_page->setContent("libri_random", $data);

$libri = $bookDAO->getRandomBook(1); 
$randomBook = $libri[0];
if ($randomBook) {
    $body_page->setContent("random_book_image", "static/img/" . $randomBook->getImagePath());
    $body_page->setContent("random_book_title", $randomBook->getTitle());
    $body_page->setContent("random_book_price", $randomBook->getPrice());
    $author = $randomBook->getAuthor();
    $body_page->setContent("random_book_author", $author ? $author->getName() : "Autore Sconosciuto");
    
    $body_page->setContent("random_book_description", $randomBook->getDescription());
    $body_page->setContent("random_book_id", $randomBook->getId());
   
}




$bookDAO = $dataLayer->getBookDAO();
$libri = $bookDAO->getRandomBook(3); 
$data = [];
$id_salvati = [];

foreach($libri as $b) {
    $prezzoOriginale = $b->getPrice();
    $prezzoScontato = number_format($prezzoOriginale * 0.90, 2); 
    $valoreSconto = number_format($prezzoOriginale * 0.10, 2);
    $data[] = [
        "random_title_offer" => $b->getTitle(), 
        "random__price_offer" => $prezzoOriginale, 
        "random_discounted_price" => $prezzoScontato, 
        "random_discount_amount" => $valoreSconto,    
        "random_author_offer" => $b->getAuthor() ? $b->getAuthor()->getName() : "Autore Sconosciuto", 
        "random_description_offer" => $b->getDescription(), 
        "random_id_offer" => $b->getId(), 
        "random_image_offer" => "static/img/" . $b->getImagePath()
    ];
    
  
    $body_page->setContent("random_title_offer", $b->getTitle());
    $body_page->setContent("random__price_offer", $prezzoOriginale);
    $body_page->setContent("random_discounted_price", $prezzoScontato); 
    $body_page->setContent("random_discount_amount", $valoreSconto); 
    $author = $b->getAuthor();
    $body_page->setContent("random_author_offer", $author ? $author->getName() : "Autore Sconosciuto");
    $body_page->setContent("random_description_offer", $b->getDescription());
    $body_page->setContent("random_id_offer", $b->getId());
  
        $body_page->setContent("random_image_offer", "static/img/" . $b->getImagePath());
   
    $id_salvati[] = $b->getId();
}
$id1 = $id_salvati[0] ?? 0;
$id2 = $id_salvati[1] ?? 0;
$id3 = $id_salvati[2] ?? 0;

// 2. Prepariamo i vecchi segnaposto singoli per sicurezza
$body_page->setContent("id_libro_1", $id1);
$body_page->setContent("id_libro_2", $id2);
$body_page->setContent("id_libro_3", $id3);

// 3. Generiamo il link completo DIRETTAMENTE a mano (Addio QueryStringBuilder!)
$offer_link = "offer.php?id1=" . $id1 . "&id2=" . $id2 . "&id3=" . $id3;
$body_page->setContent("offer_link", $offer_link);
$body_page->setContent("id_libro_1", $id_salvati[0] ?? 0);
$body_page->setContent("id_libro_2", $id_salvati[1] ?? 0);
$body_page->setContent("id_libro_3", $id_salvati[2] ?? 0);

$body_page->setContent("libri_random", $data);
?>