<?php

// Import
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/utility/AuthManager.php");

// DAO
$factory = new DataLayer(new DB_Connection());
$userDAO = $factory->getUserDAO();


// Controlla se si è provato a fare il login
if (isset($_POST["email"]) && isset($_POST["password"])) {

    $user = $userDAO->getUserByEmail($_POST["email"]);

    // Autentico l'utente
    if ($user != null && (AuthManager::verifyPasswordSHA($_POST["password"], $user->getPassword()))) {

        $_SESSION["auth"] = true;
        $_SESSION["id"] = $user->getId();
        $_SESSION["name"] = $user->getName();
        $_SESSION["surname"] = $user->getSurname();
        $_SESSION["email"] = $user->getEmail();
        $_SESSION["role"] = $user->getRole();

        // Se l'utente è un amministratore
        if (strtoupper($user->getRole()->toString()) == "ADMIN") {
            header("Location: index_admin.php");
            exit;
        }



        $redirect = isset($_REQUEST["reference"]) ? $_REQUEST["reference"] : 'index.php';
        header("Location: " . $redirect);
        exit;
    } else {
        // Se l'email o la password sono errate e quindi il login è fallito
        header("Location: login.php?error=on");
        exit;
    }
}


// Carica la pagina di login
$body_page = new Template("html/login/login.html");

// Se non è stata passata nessuna pagina di riferimento, allora riporta alla homepage
$reference = isset($_REQUEST["reference"]) ? base64_decode($_REQUEST["reference"]) : "index.php";
$body_page->setContent("reference_page", $reference);

// Se error è settato, mostra il messaggio di errore
if (isset($_GET["error"])) {
    $body_page->setContent("error", "Invalid username or password.");
}
