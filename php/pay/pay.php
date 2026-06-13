<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/QueryStringBuilder.php");
require_once("include/model/PaymentMethod.php");
require_once("include/model/Order.php");
require_once("include/model/OrderItem.php");

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $ref_encoded = base64_encode("pay.php");
    header("Location: login.php?reference=" . $ref_encoded);
    exit;
}

if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $user_id = (int) $_SESSION['user_id'];
} elseif (isset($_GET["user_id"]) && is_numeric($_GET["user_id"])) {
    $user_id = (int) $_GET["user_id"];
    $_SESSION['user_id'] = $user_id;
} elseif (isset($_POST["user_id"]) && is_numeric($_POST["user_id"])) {
    $user_id = (int) $_POST["user_id"];
    $_SESSION['user_id'] = $user_id;
} elseif (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $user_id = (int) $_SESSION['id'];
    $_SESSION['user_id'] = $user_id;
} else {
    echo "Errore: ID utente non valido.";
    exit;
}

$amount = isset($_SESSION['order_total']) ? (float) $_SESSION['order_total'] : 0.0;
$subtotal = isset($_SESSION['cart_subtotal']) ? (float) $_SESSION['cart_subtotal'] : 0.0;
$shipping_cost = isset($_SESSION['shipping_cost']) ? (float) $_SESSION['shipping_cost'] : 0.0;
$shipping_method_id = isset($_SESSION['shipping_method_id']) ? (int) $_SESSION['shipping_method_id'] : 0;
$shipping_label = $_SESSION['shipping_label'] ?? "Nessuna spedizione selezionata";

// GET → mostra pagina
if ($amount <= 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Importo non valido. Torna al carrello.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        // Importo dal POST
        $amount = isset($_POST['amount']) ? (float) $_POST['amount'] : 0.0;

        if ($amount <= 0) {
            echo json_encode([
                "success" => false,
                "message" => "Importo non valido. Torna al carrello."
            ]);
            exit;
        }

        $cardName = trim($_POST['cardName'] ?? '');
        $cardNumber = preg_replace('/\D/', '', $_POST['cardNumber'] ?? '');
        $expiry = trim($_POST['expiry'] ?? '');
        $cvc = trim($_POST['cvc'] ?? '');

        if ($cardName === '' || $cardNumber === '' || $expiry === '' || $cvc === '') {
            echo json_encode(["success" => false, "message" => "Compila tutti i campi del pagamento."]);
            exit;
        }

        if (strlen($cardNumber) < 12 || strlen($cardNumber) > 19) {
            echo json_encode(["success" => false, "message" => "Numero carta non valido."]);
            exit;
        }

        if (!preg_match('/^\d{3,4}$/', $cvc)) {
            echo json_encode(["success" => false, "message" => "CVC non valido."]);
            exit;
        }

        $dataLayer = new DataLayer(new DB_Connection());

        $userDAO = $dataLayer->getUserDAO();
        $cartDAO = $dataLayer->getCartDAO();
        $orderDAO = $dataLayer->getOrderDAO();
        $orderItemDAO = $dataLayer->getOrderItemDAO();
        $paymentMethodDAO = $dataLayer->getPaymentMethodDAO();
        $shippingMethodDAO = $dataLayer->getShippingMethodDAO();

        $user = $userDAO->getUserById($user_id);

        if ($user === null) {
            echo json_encode(["success" => false, "message" => "Utente non trovato."]);
            exit;
        }

        $cart = $cartDAO->getCartByUserId($user_id);
        $cart_items = $cart->getItems();

        if (empty($cart_items)) {
            echo json_encode(["success" => false, "message" => "Il carrello è vuoto."]);
            exit;
        }

        $payment_method_id = 1;
        $paymentMethod = $paymentMethodDAO->getPaymentMethodById($payment_method_id);

        if ($paymentMethod === null) {
            $paymentMethod = new PaymentMethod();
            $paymentMethod->setId($payment_method_id);
            $paymentMethod->setName("Carta di credito");
        }

        $shippingMethod = $shippingMethodDAO->getShippingMethodById($shipping_method_id);

        if ($shippingMethod === null) {
            echo json_encode(["success" => false, "message" => "Metodo di spedizione non valido."]);
            exit;
        }

        // CREA ORDINE
        $order = new Order();
        $order->setUser($user);
        $order->setPaymentMethod($paymentMethod);
        $order->setShippingMethod($shippingMethod);
        $order->setOrderDate(date("Y-m-d H:i:s"));
        $order->setTotal(number_format($amount, 2, '.', ''));

        $storedOrder = $orderDAO->storeOrder($order);

        if ($storedOrder === null || $storedOrder->getId() === null) {
            echo json_encode(["success" => false, "message" => "Errore durante la creazione dell'ordine."]);
            exit;
        }

        // SALVA PRODOTTI
        foreach ($cart_items as $cart_item) {
            $book = $cart_item->getBook();
            if ($book === null) continue;

            $orderItem = new OrderItem();
            $orderItem->setOrder($storedOrder);
            $orderItem->setBook($book);
            $orderItem->setQuantity((int) $cart_item->getQuantity());
            $orderItem->setUnitPrice((float) $book->getPrice());

            $orderItemDAO->storeOrderItem($orderItem);
        }

        // 🔥 SVUOTA CARRELLO (metodo reale del tuo CartDAO)
        $cartDAO->emptyCartByUser($user_id);

        $last4 = substr($cardNumber, -4);

        echo json_encode([
            "success" => true,
            "message" => "Pagamento avvenuto con successo!",
            "last4" => $last4,
            "amount" => number_format($amount, 2, '.', ''),
            "order_id" => $storedOrder->getId(),
            "redirect" => "orders.php?user_id=" . urlencode((string) $user_id)
        ]);
        exit;

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Eccezione: " . $e->getMessage()]);
        exit;
    }
}

// GET → mostra pagina
$body_page = new Template("html/pay/pay.html");
$body_page->setContent("user_id", $user_id);
$body_page->setContent("amount", number_format($amount, 2, '.', ''));
$body_page->setContent("subtotal", number_format($subtotal, 2, '.', ''));
$body_page->setContent("shipping_cost", number_format($shipping_cost, 2, '.', ''));
$body_page->setContent("shipping_method_id", $shipping_method_id);
$body_page->setContent("shipping_label", $shipping_label);



?>
