<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Utente non autenticato.']);
    exit;
}
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/models");



// Non salviamo dati sensibili nel database in chiaro.
// Qui memorizziamo solo una versione mascherata della carta in sessione come preferenza utente.
try {
    $cardName = isset($_POST['cardName']) ? trim($_POST['cardName']) : '';
    $cardNumber = isset($_POST['cardNumber']) ? preg_replace('/\s+/', '', $_POST['cardNumber']) : '';
    $expiry = isset($_POST['expiry']) ? trim($_POST['expiry']) : '';

    if ($cardNumber === '' || $cardName === '') {
        echo json_encode(['success' => false, 'message' => 'Dati di pagamento incompleti.']);
        exit;
    }

    $masked = '**** **** **** ' . substr($cardNumber, -4);

    $_SESSION['payment_info'] = [
        'cardName' => $cardName,
        'cardMasked' => $masked,
        'expiry' => $expiry
    ];

    echo json_encode(['success' => true, 'message' => 'Metodo di pagamento aggiornato (memorizzazione sicura).']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Eccezione: ' . $e->getMessage()]);
}

exit;
?>