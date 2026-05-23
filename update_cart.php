<?php
require_once __DIR__ . '/include/dbms.inc.php';
require_once __DIR__ . '/include/db/DataLayer.php';
require_once __DIR__ . '/include/db/DB_Connection.php';
header('Content-Type: application/json; charset=utf-8');

session_start();
// follow same session keys used elsewhere: 'auth' and 'id'
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true || !isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Utente non autenticato']);
    exit;
}

$dl = new DataLayer(new DB_Connection());
$cartDAO = $dl->getCartDAO();
$userId = intval($_SESSION['id']);

// Accept either cart_item_id or book_id
$cart_item_id = isset($_POST['cart_item_id']) ? intval($_POST['cart_item_id']) : null;
$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : null;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;

if ($quantity === null || $quantity < 0) {
    echo json_encode(['success' => false, 'message' => 'Quantità non valida']);
    exit;
}

try {
    $item = null;
    if ($cart_item_id) {
        if (method_exists($cartDAO, 'getCartItemByUserId')) {
            $item = $cartDAO->getCartItemByUserId($cart_item_id);
        }
    } elseif ($book_id) {
        if (method_exists($cartDAO, 'getCartItemByUserAndBook')) {
            $item = $cartDAO->getCartItemByUserAndBook($userId, $book_id);
        }
    }

    if (!$item) {
        echo json_encode(['success' => false, 'message' => 'Elemento carrello non trovato']);
        exit;
    }

    // Ensure the item belongs to the user (some models may expose getUserId())
    if (method_exists($item, 'getUserId') && $item->getUserId() != $userId) {
        echo json_encode(['success' => false, 'message' => 'Permesso negato']);
        exit;
    }

    // If quantity == 0, remove the item
    if ($quantity === 0) {
        $item_book_id = null;
        if (method_exists($item, 'getBookId')) $item_book_id = $item->getBookId();
        elseif ($book_id) $item_book_id = $book_id;

        if (method_exists($cartDAO, 'deleteCartItemById')) {
            $cartDAO->deleteCartItemById($item->getId());
        } elseif (method_exists($cartDAO, 'deleteCartItemByUserAndBook') && $item_book_id !== null) {
            $cartDAO->deleteCartItemByUserAndBook($userId, $item_book_id);
        }
        // Recompute subtotal
        // recompute subtotal by iterating items
        $cartItems = $cartDAO->getCartItemsByUserId($userId);
        $subtotal = 0.0;
        foreach ($cartItems as $ci) {
            if (method_exists($ci, 'getBook')) {
                $book = $ci->getBook();
                if ($book !== null && method_exists($book, 'getPrice')) {
                    $subtotal += (float)$book->getPrice() * $ci->getQuantity();
                }
            }
        }
        $cart_count = is_array($cartItems) ? count($cartItems) : 0;
        echo json_encode(['success' => true, 'message' => 'Elemento rimosso', 'subtotal' => $subtotal, 'removed' => true, 'cart_count' => $cart_count]);
        exit;
    }

    // Otherwise update quantity
    if (method_exists($cartDAO, 'updateCartItemQuantity')) {
        $cartDAO->updateCartItemQuantity($item->getId(), $quantity);
    } else {
        // fallback: delete and re-add or other available method
        if (method_exists($cartDAO, 'deleteCartItemById')) {
            $cartDAO->deleteCartItemById($item->getId());
        }
        if (method_exists($cartDAO, 'addToCart')) {
            // addToCart(userId, bookId, quantity)
            $item_book_id = null;
            if (method_exists($item, 'getBookId')) $item_book_id = $item->getBookId();
            elseif ($book_id) $item_book_id = $book_id;
            if ($item_book_id !== null) $cartDAO->addToCart($userId, $item_book_id, $quantity);
        }
    }

    // recompute row total and subtotal
    $bookPrice = null;
    if (method_exists($item, 'getUnitPrice')) {
        $bookPrice = floatval($item->getUnitPrice());
    } elseif (method_exists($item, 'getPrice')) {
        $bookPrice = floatval($item->getPrice());
    }
    $row_total = $bookPrice !== null ? $bookPrice * $quantity : null;

    // recompute subtotal by iterating items
    $cartItems = $cartDAO->getCartItemsByUserId($userId);
    $subtotal = 0.0;
    foreach ($cartItems as $ci) {
        if (method_exists($ci, 'getBook')) {
            $book = $ci->getBook();
            if ($book !== null && method_exists($book, 'getPrice')) {
                $subtotal += (float)$book->getPrice() * $ci->getQuantity();
            }
        }
    }
    $cart_count = is_array($cartItems) ? count($cartItems) : 0;

    echo json_encode(['success' => true, 'message' => 'Quantità aggiornata', 'row_total' => $row_total, 'subtotal' => $subtotal, 'cart_count' => $cart_count]);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Errore server: ' . $e->getMessage()]);
    exit;
}
