<?php
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
            'message' => 'Devi essere loggato per modificare il carrello.',
            'redirect' => 'login.php'
        ]);
        exit;
    }

    $reference = base64_encode('cart.php');
    header('Location: login.php?reference=' . urlencode($reference));
    exit;
}

$cart_item_id = isset($_POST['cart_item_id']) ? (int)$_POST['cart_item_id'] : 0;
$book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;

if ($cart_item_id <= 0 && $book_id <= 0) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => false,
            'message' => 'ID elemento carrello non valido.'
        ]);
        exit;
    }

    header('Location: cart.php');
    exit;
}

$dataLayer = new DataLayer(new DB_Connection());
$cartDAO = $dataLayer->getCartDAO();

$user_id = (int)$_SESSION['id'];

$deleted = false;

if ($cart_item_id > 0) {
    /** @var CartItemProxy|null $cartItem */
    $cartItem = $cartDAO->getCartItemByUserId($cart_item_id);
    if ($cartItem === null || (method_exists($cartItem, 'getUserId') && $cartItem->getUserId() !== $user_id)) {
        if ($isAjax) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false,
                'message' => 'Elemento carrello non trovato.'
            ]);
            exit;
        }
        header('Location: cart.php');
        exit;
    }
    $deleted = $cartDAO->deleteCartItemById($cart_item_id);
} else {
    $deleted = $cartDAO->deleteCartItemByUserAndBook($user_id, $book_id);
}

if (!$deleted) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => false,
            'message' => 'Impossibile rimuovere il libro dal carrello.'
        ]);
        exit;
    }
    header('Location: cart.php');
    exit;
}

$cart = $cartDAO->getCartByUserId($user_id);
$subtotal = 0.0;
foreach ($cart->getItems() as $item) {
    $book = $item->getBook();
    if ($book !== null) {
        $subtotal += (float)$book->getPrice() * $item->getQuantity();
    }
}

if ($isAjax) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'success' => true,
        'message' => 'Libro rimosso dal carrello.',
        'subtotal' => number_format($subtotal, 2, '.', ''),
        'remaining_items' => count($cart->getItems()),
        'cart_count' => count($cart->getItems())
    ]);
    exit;
}

$redirect = 'cart.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $redirect = $_SERVER['HTTP_REFERER'];
}
header('Location: ' . $redirect);
exit;
