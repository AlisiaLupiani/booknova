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

$dataLayer = new DataLayer(new DB_Connection());
$orderDAO = $dataLayer->getOrderDAO();
$orderItemDAO = $dataLayer->getOrderItemDAO();

$user_id = (int) $_SESSION['user_id'];

// 🔥 Recupera tutti gli ordini dell’utente
$orders = $orderDAO->getOrdersByUserId($user_id);

$body_page = new Template("html/ordini/ordini.html");



// 🔥 Loop sugli ordini
foreach ($orders as $order) {

    $order_id = $order->getId();
    $order_date = $order->getOrderDate();
    $order_total = number_format($order->getTotal(), 2, ',', '.');

    // 👉 Popola il blocco ORDINE
    $body_page->setContent("order_id", $order_id);
    $body_page->setContent("order_date", $order_date);
    $body_page->setContent("order_total", $order_total);

    // 🔥 Recupera gli articoli dell’ordine
    $items = $orderItemDAO->getOrderItemsByOrderId($order_id);

    // 👉 Loop sugli articoli dell’ordine
    foreach ($items as $item) {

        $book = $item->getBook();
        $title = $book->getTitle();
        $price = number_format($item->getUnitPrice(), 2, ',', '.');
        

        // 👉 Popola il blocco ITEM (true = appendi dentro foreach_item)
        $body_page->setContent("book_title", $title, true);
        $body_page->setContent("book_price", "€ " . $price, true);

        $body_page->setContent("review_link", "aggiungi_recensione.php?book_id=" . $book->getId(), true);
    }
}

?>
