<?php

/* if(!isset($_SESSION['auth'])) {
    header("Location: login.php?reference=\"cart\".php");
    exit;
} */
$body_page = new Template("html/cart/cart.html");

$userId= 1;
$dataLayer = new DataLayer(new DB_Connection());
$cartDAO = $dataLayer->getCartDAO();
$cart = $cartDAO->getCartByUser($userId);
foreach ($cart as $item) {
    $body_page->setContent("booktitle", $item-> getTitle());
    $body_page->setContent("price", $item->getPrice());
}

?>