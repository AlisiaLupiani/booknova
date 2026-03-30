<?php

/* if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"cart\".php");
    exit;
} */
$body_page = new Template("html/cart/cart.html");

// Da sostituire con l'ID preso dalla sessione
$userId= 1;

$dataLayer = new DataLayer(new DB_Connection());
$cartDAO = $dataLayer->getCartDAO();
$cart = $cartDAO->getCartByUser($userId);

foreach ($items as $item) {
    $body_page->setContent("booktitle", $item->getBook()->getTitle());
    $body_page->setContent("price", $item->getBook()->getPrice());
    $body_page->setContent("quantity", $item->getQuantity());
}

?>