<?php

$body_page = new Template("html/book_details/book_details.html");

// DAO
$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO();
$reviewDAO = $dataLayer->getReviewDAO();
$ratingDAO = $dataLayer->getRatingDAO();

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
// 3. Recupero delle recensioni e dei voti (solo tramite book_id)
$reviews = $reviewDAO->getReviewsByBook($book_id);
$ratings = $ratingDAO->getRatingsByBook($book_id);

// --- CALCOLO MEDIA E CONTEGGIO DEI VOTI ---
$total_ratings = count($ratings);
if ($total_ratings > 0) {
    $sum = 0;
    foreach ($ratings as $r) {
        $sum += $r->getValue();
    }
    $average_rating = round($sum / $total_ratings, 1);
    $review_value_string = $average_rating . " (" . $total_ratings . " votazioni)";
    
    // Generazione stringa delle stelline dinamiche per la media complessiva
    $num_stelle_piene = (int)round($average_rating);
    $num_stelle_vuote = 5 - $num_stelle_piene;
    $stars_string = str_repeat("★", $num_stelle_piene) . str_repeat("☆", $num_stelle_vuote);
} else {
    $review_value_string = "Nessuna recensione";
    $stars_string = "☆☆☆☆☆";
}

// Qui popoliamo i dati della TESTATA (eseguiti una volta sola)
$body_page->setContent("review_value", $review_value_string);
$body_page->setContent("dynamic_stars", $stars_string); 

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