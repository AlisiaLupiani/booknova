<?php

// 1. Avvia la sessione prima di fare qualsiasi controllo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/QueryStringBuilder.php");

// 2. CONTROLLO DI SICUREZZA
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $ref_encoded = base64_encode("cart.php");
    header("Location: login.php?reference=" . $ref_encoded);
    exit;
}

// 3. CONTROLLO DELL'URL
if (!isset($_GET["user_id"]) || !is_numeric($_GET["user_id"])) {
    echo "Errore: Utente non trovato o ID non valido.";
    exit();
}

// 4. Inizializziamo il template e i dati
$body_page = new Template("html/cart/cart.html");
$dataLayer = new DataLayer(new DB_Connection());

// 5. Recuperiamo i dati dal DB tramite il DataLayer
$user_id = (int)$_GET["user_id"];
$UserDAO = $dataLayer->getUserDAO();
$user = $UserDAO->getUserById($user_id);

if ($user === null) {
    echo "Errore: utente inesistente.";
    exit();
}

$cartDAO = $dataLayer->getCartDAO();
$bookDAO = $dataLayer->getBookDAO();
$shippingDAO = $dataLayer->getShippingMethodDAO();

// 6. Controlliamo se arriva un libro da aggiungere al carrello
$book_id = isset($_GET["book_id"]) && is_numeric($_GET["book_id"]) ? (int)$_GET["book_id"] : 0;
$quantity = 1;
if (isset($_POST["quantity"]) && is_numeric($_POST["quantity"])) {
    $quantity = max(1, (int)$_POST["quantity"]);
} elseif (isset($_GET["quantity"]) && is_numeric($_GET["quantity"])) {
    $quantity = max(1, (int)$_GET["quantity"]);
}

$shipping_method_id = 0;
if (isset($_POST["shipping_method_id"]) && is_numeric($_POST["shipping_method_id"])) {
    $shipping_method_id = (int)$_POST["shipping_method_id"];
} elseif (isset($_GET["shipping_method_id"]) && is_numeric($_GET["shipping_method_id"])) {
    $shipping_method_id = (int)$_GET["shipping_method_id"];
}

$shipping_method = null;
$shipping_cost = 0.0;
$shipping_label = "Nessuna spedizione selezionata";
if ($shipping_method_id > 0) {
    $shipping_method = $shippingDAO->getShippingMethodById($shipping_method_id);
    if ($shipping_method !== null) {
        $shipping_cost = $shipping_method->getCost() ?? 0.0;
        $shipping_label = $shipping_method->getName() . " - € " . number_format($shipping_cost, 2, '.', '');
    }
}

$shipping_methods = $shippingDAO->getAllShippingMethods();
$shipping_options = "";
foreach ($shipping_methods as $method) {
    $method_id = $method->getId();
    if ($method_id === null) continue;
    $selected = ($method_id === $shipping_method_id) ? ' selected' : '';
    $shipping_options .= '<option value="' . $method_id . '"' . $selected . '>'
        . htmlspecialchars($method->getName()) . ' - € ' . number_format($method->getCost() ?? 0.0, 2, '.', '')
        . '</option>';
}
$body_page->setContent("shipping_options", $shipping_options);
$body_page->setContent("user_id", $user_id);
$body_page->setContent("shipping_method_id", $shipping_method_id);
$body_page->setContent("shipping_label", $shipping_label);

if ($book_id > 0) {
    $book = $bookDAO->getBookById($book_id);
    if ($book !== null) {
        $existingItem = $cartDAO->getCartItemByUserAndBook($user_id, $book_id);
        if ($existingItem !== null) {
            $cartDAO->updateCartItemQuantity(
                $existingItem->getId(),
                $existingItem->getQuantity() + $quantity
            );
        } else {
            $cartDAO->addCartItem($user_id, $book_id, $quantity);
        }
    }
}

// 7. Recuperiamo il carrello dell'utente
$cart = $cartDAO->getCartByUserId($user_id);
$cart_items = $cart->getItems();

// --- INIZIALIZZIAMO IL TOTALE DELL'ORDINE ---
$totale_ordine = 0.0;

// 8. Ciclo per popolare il template
foreach ($cart_items as $cart_item) {
    $libro = $cart_item->getBook();
    $quantita = $cart_item->getQuantity();

    if ($libro !== null) {
        $body_page->setContent("bookimageallgenre", "static/img/" . $libro->getImagePath());
        $body_page->setContent("booktitleallgenre", $libro->getTitle());
        $body_page->setContent("authorallgenre", $libro->getAuthor()->getName());
        $body_page->setContent("priceallgenre", $libro->getPrice());
        $body_page->setContent("quantity", $quantita);
        $body_page->setContent("bookidallgenre", $libro->getId());
        $body_page->setContent("cartitemid", $cart_item->getId());

        $totale_singolo_libro = ((float)$libro->getPrice() * (int)$quantita);
        $body_page->setContent("single_item_total", number_format($totale_singolo_libro, 2, '.', ''));

        $string_builder = new QueryStringBuilder("book_details.php");
        $string_builder->add("book_id", $libro->getId());
        $body_page->setContent("bookallgenrehrefid", $string_builder->build());

        $totale_ordine += $totale_singolo_libro;
    }
}

// 9. Inviamo il totale finito al tag del template
$body_page->setContent("subtotal", number_format($totale_ordine, 2, '.', ''));
$body_page->setContent("spedizione", $shipping_label);
$body_page->setContent("order_total", number_format($totale_ordine + $shipping_cost, 2, '.', ''));
$body_page->setContent("shipping_cost", number_format($shipping_cost, 2, '.', ''));
$body_page->setContent("shipping_method_id", $shipping_method_id);

?>