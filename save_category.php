<?php
header("Content-Type: application/json");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Percorsi corretti (sei dentro /php/aggiungi_libro/)
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/Category.php");
require_once("include/model/proxy/CategoryProxy.php");

$dataLayer = new DataLayer(new DB_Connection());
$categoryDAO = $dataLayer->getCategoryDAO();

// Campi richiesti
$required = ["name"];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === "") {
        echo json_encode(["success" => false, "message" => "Campo mancante: $field"]);
        exit;
    }
}






// CREA OGGETTO CATEGORY (CategoryProxy)
$category = new CategoryProxy($dataLayer);
$category->setName($_POST["name"]);


// Foreign keys
$category->setName($_POST["name"]);


// SALVA NEL DB
$result = $categoryDAO->storeCategory($category);

echo json_encode([
    "success" => $result ? true : false,
    "message" => $result ? "Categoria aggiunta con successo" : "Errore nel salvataggio"
]);
