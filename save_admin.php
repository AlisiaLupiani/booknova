<?php
header("Content-Type: application/json");

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/User.php");
require_once("include/model/proxy/UserProxy.php");
require_once("include/model/Role.php");

$dataLayer = new DataLayer(new DB_Connection());
$userDAO = $dataLayer->getUserDAO();

// Verifica che esista il RoleDAO (se il tuo DataLayer lo espone)
$roleDAO = null;
if (method_exists($dataLayer, 'getRoleDAO')) {
    $roleDAO = $dataLayer->getRoleDAO();
}

$required = ["name", "surname", "email", "password", "role"];
foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === "") {
        echo json_encode(["success" => false, "message" => "Campo mancante: $field"]);
        exit;
    }
}

// Controllo email duplicata se il DAO fornisce il metodo
if (method_exists($userDAO, 'getUserByEmail')) {
    $existing = $userDAO->getUserByEmail(trim($_POST['email']));
    if ($existing) {
        echo json_encode(["success" => false, "message" => "Email già registrata"]);
        exit;
    }
}

// Creazione oggetto User (proxy)
$user = new UserProxy($dataLayer);
$user->setName(trim($_POST["name"]));
$user->setSurname(trim($_POST["surname"]));
$user->setEmail(trim($_POST["email"]));

// Hash della password prima di salvarla
$hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
$user->setPassword($hashedPassword);

// Risoluzione del ruolo: può essere un ID o un nome
$roleInput = trim($_POST["role"]);
$roleObj = null;

if ($roleDAO !== null) {
    // Se è numerico, proviamo per ID
    if (is_numeric($roleInput)) {
        $roleObj = $roleDAO->getRoleById((int)$roleInput);
    } else {
        // Cerchiamo per nome (case-insensitive) scorrendo tutti i ruoli
        if (method_exists($roleDAO, 'getAllRoles')) {
            $allRoles = $roleDAO->getAllRoles();
            foreach ($allRoles as $r) {
                // getRuolo() è il getter nel tuo Role
                if (strcasecmp($r->getRuolo(), $roleInput) === 0) {
                    $roleObj = $r;
                    break;
                }
            }
        }
    }

    // Se non esiste, creiamo il ruolo (opzionale)
    if ($roleObj === null) {
        // Creiamo e salviamo un nuovo ruolo con il nome fornito
        $newRole = new Role();
        $newRole->setRuolo($roleInput);
        // storeRole ritorna l'oggetto con id impostato oppure null
        if (method_exists($roleDAO, 'storeRole')) {
            $roleObj = $roleDAO->storeRole($newRole);
        } else {
            // Se non possiamo salvarlo, lasciamo roleObj null e gestiamo l'errore sotto
            $roleObj = null;
        }
    }
}

// Se non abbiamo ottenuto un oggetto Role, ritorniamo errore
if ($roleObj === null) {
    echo json_encode(["success" => false, "message" => "Ruolo non valido o RoleDAO non disponibile"]);
    exit;
}

// Impostiamo l'oggetto Role sull'utente
$user->setRole($roleObj);

// Salvataggio
$result = $userDAO->storeUser($user);

echo json_encode([
    "success" => $result ? true : false,
    "message" => $result ? "Admin aggiunto con successo" : "Errore nel salvataggio"
]);
