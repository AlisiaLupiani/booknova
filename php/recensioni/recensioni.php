<?php

$body_page = new Template("html/recensioni/recensioni.html");
$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO();
$reviewDAO = $dataLayer->getReviewDAO();
$ratingDAO = $dataLayer->getRatingDAO();

// 1. Controllo validità dell'ID del libro
if (!isset($_GET["book_id"]) || !is_numeric($_GET["book_id"])) {
    echo "Errore: Libro non trovato o ID non valido.";
    exit(); 
}

$book_id = (int)$_GET["book_id"];

// 2. Controllo esistenza del libro
$book = $bookDAO->getBookById($book_id);
if (!$book) {
    echo "Errore: Il libro richiesto non esiste nel nostro database.";
    exit();
}

// Impostiamo un eventuale titolo del libro nel template
$body_page->setContent("book_title", $book->getTitle());


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
// ------------------------------------------


// Usiamo il ciclo for classico con setContent e il parametro true per accodare
$total_reviews = count($reviews);

// Costruiamo una mappa userId => rating value per evitare mismatch d'indice
$ratingsMap = [];
foreach ($ratings as $rt) {
    // Preferiamo usare getUserId() per non forzare ulteriori fetch lazy
    $userId = method_exists($rt, 'getUserId') ? $rt->getUserId() : ($rt->getUser() ? $rt->getUser()->getId() : null);
    if ($userId !== null) {
        $ratingsMap[$userId] = $rt->getValue();
    }
}

for ($i = 0; $i < $total_reviews; $i++) {
    
    $current_review = $reviews[$i];
    
    // Recuperiamo il voto corrispondente usando la mappa (per evitare mismatch d'ordine)
    $reviewUserId = $current_review->getUser() ? $current_review->getUser()->getId() : null;
    $current_rating_value = ($reviewUserId !== null && isset($ratingsMap[$reviewUserId])) ? $ratingsMap[$reviewUserId] : "N/D";

    // NOTA: Usiamo nomi univoci ("review_rating" e "single_comment") per non sovrascrivere "review_value"
    $body_page->setContent("reviewer_name", $current_review->getUser()->getName(), true);
    $body_page->setContent("reviewer_surname", $current_review->getUser()->getSurname(), true);
    $body_page->setContent("review_comment", $current_review->getContent(), true);
    $body_page->setContent("review_date", $current_review->getDate(), true);
    $body_page->setContent("review_rating", $current_rating_value, true); 
}



?>