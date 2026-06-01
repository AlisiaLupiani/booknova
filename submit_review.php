<?php
// Endpoint AJAX per salvare recensione e voto, struttura simile ad add_to_wishlist.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carichiamo la configurazione DB prima di includere DB_Connection.php

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/Review.php");
require_once("include/model/Rating.php");


function isAjaxRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') return true;
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') return true;
    if (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) return true;
    return false;
}

header('Content-Type: application/json; charset=UTF-8');

if (!isAjaxRequest()) {
    echo json_encode(['success' => false, 'message' => 'Richiesta non valida.']);
    exit;
}

// Check auth
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true || !isset($_SESSION['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Devi essere loggato per aggiungere recensioni.',
        'redirect' => 'login.php'
    ]);
    exit;
}

$book_id = isset($_POST['bookId']) ? (int)$_POST['bookId'] : 0;
if ($book_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID libro non valido.']);
    exit;
}

$user_id = (int)$_SESSION['id'];
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
$ratingValue = isset($_POST['rating']) ? (int)$_POST['rating'] : null;

$dataLayer = new DataLayer(new DB_Connection());
$reviewDAO = $dataLayer->getReviewDAO();
$ratingDAO = $dataLayer->getRatingDAO();
$bookDAO = $dataLayer->getBookDAO();

// Verify book exists
$book = $bookDAO->getBookById($book_id);
if ($book === null) {
    echo json_encode(['success' => false, 'message' => 'Libro non trovato.']);
    exit;
}

if ($comment === '') {
    echo json_encode(['success' => false, 'message' => 'Il testo della recensione non può essere vuoto.']);
    exit;
}

if ($ratingValue === null || $ratingValue < 1 || $ratingValue > 5) {
    echo json_encode(['success' => false, 'message' => 'Valutazione non valida.']);
    exit;
}

try {
    // Usa il Proxy per coerenza
    $review = new ReviewProxy($dataLayer);
    $review->setUserId($user_id);
    $review->setBookId($book_id);
    $review->setContent($comment);
    $review->setDate(date('Y-m-d H:i:s'));

    $saved = $reviewDAO->storeReview($review);

    $rating = new RatingProxy($dataLayer);
    $rating->setUserId($user_id);
    $rating->setBookId($book_id);
    $rating->setValue($ratingValue);
    $rating->setDate(date('Y-m-d H:i:s'));
    $savedRating = $ratingDAO->storeRating($rating);

    if ($saved !== null) {
        echo json_encode(['success' => true, 'message' => 'Recensione aggiunta con successo.', 'redirect' => 'book_details.php?book_id=' . $book_id, 'in_review' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore durante il salvataggio della recensione.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Errore: ' . $e->getMessage()]);
}

exit;

?>
