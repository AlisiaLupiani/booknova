<?php

// 1. Avvia la sessione
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/QueryStringBuilder.php");

// 2. Controllo login
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $ref_encoded = base64_encode("pay.php");
    header("Location: login.php?reference=" . $ref_encoded);
    exit;
}

// 3. Controllo user_id
if (!isset($_GET["user_id"]) || !is_numeric($_GET["user_id"])) {
    echo json_encode(["success" => false, "message" => "Errore: ID utente non valido."]);
    exit;
}

$user_id = (int)$_GET["user_id"];

// 4. Template e DataLayer
$body_page = new Template("html/pay/pay.html");
$dataLayer = new DataLayer(new DB_Connection());

// 5. Se arriva il pagamento via POST (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once("include/model/Payment.php");

    $payment = new Payment();
    $payment->setUserId($user_id);
    $payment->setCardName($_POST['cardName']);
    $payment->setCardNumber(password_hash($_POST['cardNumber'], PASSWORD_DEFAULT)); // sicurezza
    $payment->setExpiry($_POST['expiry']);
    $payment->setAmount($_POST['amount']);

    // DAO pagamento
    $PagamentoDAO = $dataLayer->getPaymentDAO();
    $result = $PagamentoDAO->storePayment($payment);

    if ($result) {
        echo json_encode([
            "success" => true,
            "message" => "Pagamento avvenuto con successo! Il tuo ordine sarà preso in carico."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Errore durante il pagamento."
        ]);
    }

    exit;
}

?>
