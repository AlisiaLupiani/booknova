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
require_once("include/models.php");    


try {
    $dataLayer = new DataLayer(new DB_Connection());
    $userDAO = $dataLayer->getUserDAO();

    $userId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'ID utente non trovato in sessione.']);
        exit;
    }

    $user = $userDAO->getUserById($userId);
    if ($user === null) {
        echo json_encode(['success' => false, 'message' => 'Utente non trovato.']);
        exit;
    }

    $city = isset($_POST['cityName']) ? trim($_POST['cityName']) : '';
    $prov = isset($_POST['provincia']) ? trim($_POST['provincia']) : '';
    $cap = isset($_POST['cap']) ? trim($_POST['cap']) : '';
    $via = isset($_POST['via']) ? trim($_POST['via']) : '';

    $parts = [];
    if ($via !== '') $parts[] = $via;
    if ($cap !== '') $parts[] = $cap;
    if ($city !== '') $parts[] = $city;
    if ($prov !== '') $parts[] = $prov;

    $indirizzo = implode(', ', $parts);

    $user->setIndirizzo($indirizzo);

    $stored = $userDAO->storeUser($user);
    if ($stored) {
        echo json_encode(['success' => true, 'message' => 'Indirizzo aggiornato con successo.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento dell\'indirizzo.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Eccezione: ' . $e->getMessage()]);
}

exit;
?>