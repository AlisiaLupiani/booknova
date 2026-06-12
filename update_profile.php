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
require_once("include/model/User.php");


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

    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;

    if ($name !== null && $name !== '') $user->setName($name);
    if ($email !== null && $email !== '') $user->setEmail($email);

    // Se viene fornito un telefono, lo salviamo dentro il campo INDIRIZZO
    if ($phone !== null && $phone !== '') {
        $current = $user->getIndirizzo();
        // se già presente, conserviamo e aggiungiamo/aggiorniamo il telefono precedente
        if ($current && strpos($current, 'Telefono:') !== false) {
            // sostituisci la riga telefono
            $parts = preg_split('/\r?\n/', $current);
            $found = false;
            foreach ($parts as &$p) {
                if (strpos($p, 'Telefono:') !== false) {
                    $p = 'Telefono: ' . $phone;
                    $found = true;
                    break;
                }
            }
            if (!$found) $parts[] = 'Telefono: ' . $phone;
            $newIndirizzo = implode("\n", $parts);
        } else {
            $newIndirizzo = trim(($current ? $current . "\n" : '') . 'Telefono: ' . $phone);
        }
        $user->setIndirizzo($newIndirizzo);
    }

    $stored = $userDAO->storeUser($user);
    if ($stored) {
        echo json_encode(['success' => true, 'message' => 'Profilo aggiornato con successo.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento del profilo.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Eccezione: ' . $e->getMessage()]);
}

exit;
?>