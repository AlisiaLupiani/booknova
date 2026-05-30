<?php
// Script per registrazione nuovo utente
// Gestisce richieste AJAX per registrare un nuovo utente nel database

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/AuthManager.php");
require_once("include/model/User.php");

header('Content-Type: application/json; charset=UTF-8');

// Ricevi i dati dal form
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$shipping_address = isset($_POST['shipping_address']) ? trim($_POST['shipping_address']) : '';

// ============================================
// VALIDAZIONI
// ============================================

// Email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Inserisci un indirizzo email valido.'
    ]);
    exit;
}

// Nome
if (empty($name) || strlen($name) < 2) {
    echo json_encode([
        'success' => false,
        'message' => 'Il nome deve avere almeno 2 caratteri.'
    ]);
    exit;
}

// Cognome
if (empty($surname) || strlen($surname) < 2) {
    echo json_encode([
        'success' => false,
        'message' => 'Il cognome deve avere almeno 2 caratteri.'
    ]);
    exit;
}

// Password
if (empty($password) || strlen($password) < 6) {
    echo json_encode([
        'success' => false,
        'message' => 'La password deve avere almeno 6 caratteri.'
    ]);
    exit;
}

// Indirizzo
if (empty($shipping_address) || strlen($shipping_address) < 5) {
    echo json_encode([
        'success' => false,
        'message' => 'L\'indirizzo deve avere almeno 5 caratteri.'
    ]);
    exit;
}

// ============================================
// VERIFICA EMAIL NON ESISTA
// ============================================

$dataLayer = new DataLayer(new DB_Connection());
$userDAO = $dataLayer->getUserDAO();

$existing_user = $userDAO->getUserByEmail($email);
if ($existing_user !== null) {
    echo json_encode([
        'success' => false,
        'message' => 'Questa email è già registrata nel sistema.'
    ]);
    exit;
}

// ============================================
// CREA NUOVO UTENTE
// ============================================

try {
    $user = new User();
    $user->setName($name);
    $user->setSurname($surname);
    $user->setEmail($email);
    $user->setIndirizzo($shipping_address);
    
    // Crittografa la password con SHA256
    $password_hash = AuthManager::encryptPasswordSHA($password);
    $user->setPassword($password_hash);
    
    // Salva l'utente nel database
    $result = $userDAO->storeUser($user);
    
    if ($result !== null && $result->getId() !== null) {
        echo json_encode([
            'success' => true,
            'message' => 'Registrazione completata con successo! Accedi con le tue credenziali.',
            'redirect' => 'login.php'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Errore durante il salvataggio dell\'utente nel database.'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Errore: ' . $e->getMessage()
    ]);
}

exit;
?>
