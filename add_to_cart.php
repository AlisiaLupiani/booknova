<?php
// Aggiunge un libro al carrello dell'utente loggato.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

function isAjaxRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return true;
    }
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        return true;
    }
    if (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        return true;
    }
    return false;
}

$isAjax = isAjaxRequest();

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true || !isset($_SESSION['id'])) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => false,
            'message' => 'Devi essere loggato per aggiungere libri al carrello.',
            'redirect' => 'login.php'
        ]);
        exit;
    }

    $reference = base64_encode('book_details.php?book_id=' . (int)($_REQUEST['book_id'] ?? 0));
    header('Location: login.php?reference=' . urlencode($reference));
    exit;
}

$book_id = isset($_REQUEST['book_id']) ? (int)$_REQUEST['book_id'] : 0;
if ($book_id <= 0) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => false,
            'message' => 'ID libro non valido.'
        ]);
        exit;
    }

    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

$dataLayer = new DataLayer(new DB_Connection());
$cartDAO = $dataLayer->getCartDAO();
$bookDAO = $dataLayer->getBookDAO();

$book = $bookDAO->getBookById($book_id);
if ($book === null) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => false,
            'message' => 'Libro non trovato.'
        ]);
        exit;
    }

    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

$user_id = (int)$_SESSION['id'];
$existingItem = $cartDAO->getCartItemByUserAndBook($user_id, $book_id);
$quantity = 1;

if ($existingItem !== null) {
    $cartDAO->updateCartItemQuantity(
        $existingItem->getId(),
        $existingItem->getQuantity() + $quantity
    );
} else {
    $cartDAO->addCartItem($user_id, $book_id, $quantity);
}

if ($isAjax) {
    // compute cart count
    $cart_items = $cartDAO->getCartItemsByUserId($user_id);
    $cart_count = is_array($cart_items) ? count($cart_items) : 0;

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'success' => true,
        'message' => 'Libro aggiunto al carrello.',
        'cart_count' => $cart_count
    ]);
    exit;
}

$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
if (!empty($redirect)) {
    if (strpos($redirect, '?') !== false) {
        $redirect .= '&added=1';
    } else {
        $redirect .= '?added=1';
    }
}
header('Location: ' . $redirect);
exit;
