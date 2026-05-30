<?php
// Script per cambio password
// Gestisce richieste AJAX per validare email e aggiornare password nel database

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/AuthManager.php");

header('Content-Type: application/json; charset=UTF-8');

$action = isset($_POST['action']) ? $_POST['action'] : '';

// ============================================
// STEP 1: Verifica email e prepara cambio password
// ============================================
if ($action === 'verify_email') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    
    // Validazione
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email non fornita.']);
        exit;
    }
    
    if (empty($new_password) || strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'La password deve avere almeno 6 caratteri.']);
        exit;
    }
    
    // Verifica che l'email esista nel database
    $dataLayer = new DataLayer(new DB_Connection());
    $userDAO = $dataLayer->getUserDAO();
    $user = $userDAO->getUserByEmail($email);
    
    if ($user === null) {
        echo json_encode(['success' => false, 'message' => 'Email non trovata nel sistema.']);
        exit;
    }
    
    // Salva dati temporaneamente in sessione per il prossimo step
    $_SESSION['password_change'] = [
        'user_id' => $user->getId(),
        'email' => $email,
        'new_password' => $new_password,
        'old_password_hash' => $user->getPassword()
    ];
    
    echo json_encode([
        'success' => true,
        'message' => 'Email verificata. Inserisci la vecchia password per continuare.',
        'user_name' => $user->getName()
    ]);
    exit;
}

// ============================================
// STEP 2: Verifica vecchia password e aggiorna
// ============================================
if ($action === 'verify_old_password') {
    $old_password = isset($_POST['old_password']) ? $_POST['old_password'] : '';
    
    // Validazione
    if (empty($old_password)) {
        echo json_encode(['success' => false, 'message' => 'Inserisci la vecchia password.']);
        exit;
    }
    
    // Verifica che i dati siano in sessione
    if (!isset($_SESSION['password_change'])) {
        echo json_encode(['success' => false, 'message' => 'Sessione scaduta. Ricomincia il processo.']);
        exit;
    }
    
    $change_data = $_SESSION['password_change'];
    
    // Verifica che la vecchia password sia corretta
    if (!AuthManager::verifyPasswordSHA($old_password, $change_data['old_password_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Vecchia password non corretta.']);
        exit;
    }
    
    // Aggiorna la password nel database
    $dataLayer = new DataLayer(new DB_Connection());
    $userDAO = $dataLayer->getUserDAO();
    
    try {
        $new_password_hash = AuthManager::encryptPasswordSHA($change_data['new_password']);
        
        // Usa il metodo per aggiornare la password
        $result = $userDAO->updatePassword($change_data['user_id'], $new_password_hash);
        
        if ($result) {
            // Pulisci la sessione
            unset($_SESSION['password_change']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Password aggiornata con successo! Accedi con la nuova password.',
                'redirect' => 'login.php'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento della password nel database.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Errore: ' . $e->getMessage()]);
    }
    exit;
}

// Se l'action non è riconosciuta
echo json_encode(['success' => false, 'message' => 'Azione non riconosciuta.']);
exit;
?>
