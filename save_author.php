<?php
header("Content-Type: application/json");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Percorsi corretti (sei dentro /php/aggiungi_libro/)
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/Author.php");
require_once("include/model/proxy/AuthorProxy.php");

$dataLayer = new DataLayer(new DB_Connection());
$authorDAO = $dataLayer->getAuthorDAO();

// Campi richiesti
$required = ["name", "biografia"];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === "") {
        echo json_encode(["success" => false, "message" => "Campo mancante: $field"]);
        exit;
    }
}






// CREA OGGETTO AUTHOR (AuthorProxy)
$author = new AuthorProxy($dataLayer);
$author->setName($_POST["name"]);
$author->setBiography($_POST["biografia"]);


// Foreign keys
$author->setName($_POST["name"]);
$author->setBiography($_POST["biografia"]);




// SALVA NEL DB
$result = $authorDAO->storeAuthor($author);

echo json_encode([
    "success" => $result ? true : false,
    "message" => $result ? "Autore aggiunto con successo" : "Errore nel salvataggio"
]);
