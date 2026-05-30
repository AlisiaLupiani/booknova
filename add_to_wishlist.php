<?php
// Add or remove book from user's wishlist
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/Wishlist.php");
require_once("include/model/proxy/WishlistProxy.php");

function isAjaxRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') return true;
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') return true;
    if (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) return true;
    return false;
}

$isAjax = isAjaxRequest();
header('Content-Type: application/json; charset=UTF-8');

// Check auth
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true || !isset($_SESSION['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Devi essere loggato per aggiungere libri alla wishlist.',
        'redirect' => 'login.php'
    ]);
    exit;
}

$book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
if ($book_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID libro non valido.']);
    exit;
}

$user_id = (int)$_SESSION['id'];
$dataLayer = new DataLayer(new DB_Connection());
$wishlistDAO = $dataLayer->getWishListDAO();
$bookDAO = $dataLayer->getBookDAO();

// Verify book exists
$book = $bookDAO->getBookById($book_id);
if ($book === null) {
    echo json_encode(['success' => false, 'message' => 'Libro non trovato.']);
    exit;
}

// Check if book is already in wishlist
$wishlist_items = $wishlistDAO->getWishlistByUser($user_id);
$book_in_wishlist = false;

foreach ($wishlist_items as $item) {
    if ($item->getBookId() === $book_id) {
        $book_in_wishlist = true;
        break;
    }
}

if ($book_in_wishlist) {
    // Remove from wishlist
    try {
        $success = $wishlistDAO->removeBookFromWishlist($user_id, $book_id);
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Libro rimosso dalla wishlist.' : 'Errore durante la rimozione dalla wishlist.',
            'in_wishlist' => false
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Errore: ' . $e->getMessage()]);
    }
    exit;
}

// Add to wishlist
try {
    // QUI È LA CORREZIONE FONDAMENTALE
    $wishlist = new WishlistProxy($dataLayer);

    $wishlist->setCreatedAt(date('Y-m-d H:i:s'));
    $wishlist->setUserId($user_id);
    $wishlist->setBookId($book_id);

    $result = $wishlistDAO->storeWishlist($wishlist);

    if ($result !== null) {
        echo json_encode(['success' => true, 'message' => 'Libro aggiunto alla wishlist.', 'in_wishlist' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiunta alla wishlist.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Errore: ' . $e->getMessage()]);
}

exit;
