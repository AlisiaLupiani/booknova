<?php

// Import
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/AuthManager.php");

// Assicurati che la sessione sia avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DAO
$factory = new DataLayer(new DB_Connection());
$userDAO = $factory->getUserDAO();


// Controlla se si è provato a fare il login tramite form POST
if (isset($_POST["email"]) && isset($_POST["password"])) {

    $user = $userDAO->getUserByEmail($_POST["email"]);

    // Autentico l'utente
    if ($user != null && (AuthManager::verifyPasswordSHA($_POST["password"], $user->getPassword()))) {

        $_SESSION["auth"] = true;
      $_SESSION["id"] = $user->getId();
$_SESSION["user_id"] = $user->getId();

        $_SESSION["name"] = $user->getName();
        $_SESSION["surname"] = $user->getSurname();
        $_SESSION["email"] = $user->getEmail();
        
        // Salviamo SOLO la stringa per evitare l'errore "Serialization of PDO"
        $_SESSION["role"] = strtoupper($user->getRole()->toString());

        // Se l'utente è un amministratore, reindirizza alla dashboard admin
        if ($_SESSION["role"] == "ADMIN") {
            header("Location: index_admin.php?user_id=" . $user->getId());
            exit;
        }

        // --- GESTIONE RE-DIRECT CON USER ID NELL'URL ---
        $defaultRedirect = 'index.php?user_id=' . $user->getId(); 
        $redirect = $defaultRedirect;

        if (isset($_REQUEST["reference"]) && !empty($_REQUEST["reference"])) {
            $decoded = base64_decode($_REQUEST["reference"], true);

            if ($decoded !== false && preg_match('/^[a-zA-Z0-9_\-\.\/\?&\=]+$/', $decoded)) {
                $redirect = $decoded;

                if (preg_match('/^index\.php(\?user_id=)?$/', $redirect)) {
                    $redirect = $defaultRedirect;
                }
            }
        }

        header("Location: " . $redirect);
        exit;
        
    } else {
        // Se l'email o la password sono errate e quindi il login è fallito
        header("Location: login.php?error=on");
        exit;
    }
}


// Carica la pagina di login (Interfaccia Grafica)
$body_page = new Template("html/login/login.html");

// --- GESTIONE DEL PULSANTE ACCEDI / LOGOUT ---
// Controlliamo se l'utente è già loggato per mostrare il bottone corretto nel template
if (isset($_SESSION["auth"]) && $_SESSION["auth"] === true) {
    $login_button = '<a href="logout.php" class="nav-link">Logout (' . $_SESSION["name"] . ')</a>';
} else {
    $login_button = '<a href="login.php" class="nav-link">Accedi</a>';
}
$body_page->setContent("auth_button", $login_button);


// --- GESTIONE REFERENCE PER IL FORM HTML ---
// Se c'è già un reference nell'URL lo teniamo pulito così com'è per passarlo al form, altrimenti creiamo un Base64 di "index.php"
if (isset($_REQUEST["reference"]) && !empty($_REQUEST["reference"])) {
    $reference = $_REQUEST["reference"];
} else {
    $reference = base64_encode("index.php");
}
$body_page->setContent("reference_page", $reference);


// Se error è settato nell'URL, mostra il messaggio di errore sul template
if (isset($_GET["error"])) {
    $body_page->setContent("error", "Invalid username or password.");
}

