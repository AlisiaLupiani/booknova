<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $ref_encoded = base64_encode("ordini.php");
    header("Location: login.php?reference=" . $ref_encoded);
    exit;
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/db/dao/OrderDAO.php");
require_once("include/db/dao/OrderItemDAO.php");

try {
    $dataLayer = new DataLayer(new DB_Connection());
    $orderDAO = $dataLayer->getOrderDAO();
    $orderItemDAO = $dataLayer->getOrderItemDAO();
} catch (Exception $e) {
    file_put_contents('error_log.txt', date('c') . " - Errore inizializzazione DataLayer: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo "Errore server. Controlla i log.";
    exit;
}

$user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
if ($user_id <= 0) {
    file_put_contents('error_log.txt', date('c') . " - user_id non valido in sessione: " . var_export($_SESSION, true) . PHP_EOL, FILE_APPEND);
    echo "Utente non valido.";
    exit;
}

// Recupera ordini
try {
    $orders = $orderDAO->getOrdersByUserId($user_id);
} catch (Exception $e) {
    file_put_contents('error_log.txt', date('c') . " - Errore getOrdersByUserId: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    $orders = [];
}

// Verifica tipo
if (!is_array($orders)) {
    file_put_contents('error_log.txt', date('c') . " - getOrdersByUserId non ha restituito un array: " . var_export($orders, true) . PHP_EOL, FILE_APPEND);
    $orders = [];
}

$body_page = new Template("html/ordini/ordini.html");

// Se non ci sono ordini, mostra messaggio nella pagina (dipende dal template)
if (count($orders) === 0) {
    // Imposta un messaggio visibile nel template
    $body_page->setContent("no_orders_message", "Non hai ordini al momento.");
} else {
    // Loop sugli ordini
    foreach ($orders as $orderIndex => $order) {
        if (!is_object($order) || !method_exists($order, 'getId')) {
            file_put_contents('error_log.txt', date('c') . " - Elemento ordini non valido all'indice $orderIndex: " . var_export($order, true) . PHP_EOL, FILE_APPEND);
            continue;
        }

        $order_id = $order->getId();
        $order_date = method_exists($order, 'getOrderDate') ? $order->getOrderDate() : '';
        $order_total = method_exists($order, 'getTotal') ? number_format($order->getTotal(), 2, ',', '.') : '0,00';

        // Popola il blocco ORDINE (se il template usa blocchi, assicurati che i nomi corrispondano)
        $body_page->setContent("order_id", $order_id, true);
        $body_page->setContent("order_date", $order_date, true);
        $body_page->setContent("order_total", $order_total, true);

        // Recupera articoli
        try {
            $items = $orderItemDAO->getOrderItemsByOrderId($order_id);
        } catch (Exception $e) {
            file_put_contents('error_log.txt', date('c') . " - Errore getOrderItemsByOrderId per ordine $order_id: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            $items = [];
        }

        if (!is_array($items)) {
            file_put_contents('error_log.txt', date('c') . " - getOrderItemsByOrderId non ha restituito array per ordine $order_id: " . var_export($items, true) . PHP_EOL, FILE_APPEND);
            $items = [];
        }

        // Se il template richiede che i campi degli item non siano accumulati tra ordini,
        // potresti dover resettare i placeholder qui (dipende dall'implementazione Template).
        // Esempio: $body_page->setContent("book_title", "", false);

        foreach ($items as $itemIndex => $item) {
            if (!is_object($item) || !method_exists($item, 'getBook')) {
                file_put_contents('error_log.txt', date('c') . " - Item non valido ordine $order_id indice $itemIndex: " . var_export($item, true) . PHP_EOL, FILE_APPEND);
                continue;
            }

            $book = $item->getBook();
            if (!is_object($book) || !method_exists($book, 'getTitle')) {
                file_put_contents('error_log.txt', date('c') . " - Book non valido in item ordine $order_id indice $itemIndex: " . var_export($book, true) . PHP_EOL, FILE_APPEND);
                $title = "Titolo non disponibile";
                $bookId = 0;
            } else {
                $title = $book->getTitle();
                $bookId = method_exists($book, 'getId') ? $book->getId() : 0;
            }

            $unitPrice = method_exists($item, 'getUnitPrice') ? $item->getUnitPrice() : 0;
            $price = number_format($unitPrice, 2, ',', '.');

            // Popola i campi ITEM (usa append = true per accumulare)
            $body_page->setContent("book_title", htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), true);
            $body_page->setContent("book_price", "€ " . $price, true);
            $body_page->setContent("review_link", "aggiungi_recensione.php?book_id=" . (int)$bookId, true);
        }
    }
}

