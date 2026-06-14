<?php
header("Content-Type: application/json");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Percorsi corretti (sei dentro /php/aggiungi_libro/)
require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/Book.php");
require_once("include/model/proxy/BookProxy.php");

$dataLayer = new DataLayer(new DB_Connection());
$bookDAO = $dataLayer->getBookDAO();

// Campi richiesti
$required = ["title", "price", "description", "author_id", "publisher_id", "category_id", "format_id", "condition_id", "pages", "year"];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === "") {
        echo json_encode(["success" => false, "message" => "Campo mancante: $field"]);
        exit;
    }
}

// Controllo immagine
if (!isset($_FILES["image"])) {
    echo json_encode(["success" => false, "message" => "Immagine mancante"]);
    exit;
}

// Salvataggio immagine
$uploadDir = __DIR__ . "/../static/img/";

if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$filename = basename($_FILES["image"]["name"]);
$targetPath = $uploadDir . $filename;

if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
    echo json_encode(["success" => false, "message" => "Errore nel caricamento immagine"]);
    exit;
}




// CREA OGGETTO BOOK (BookProxy)
$book = new BookProxy($dataLayer);
$book->setTitle($_POST["title"]);
$book->setPrice($_POST["price"]);
$book->setDescription($_POST["description"]);
$book->setPages($_POST["pages"]);
$book->setPublicationYear($_POST["year"]);
$book->setImagePath($filename);


// Foreign keys
$book->setAuthorId($_POST["author_id"]);
$book->setPublisherId($_POST["publisher_id"]);
$book->setCategoryId($_POST["category_id"]);
$book->setFormatId($_POST["format_id"]);
$book->setConditionId($_POST["condition_id"]);

// SALVA NEL DB
$result = $bookDAO->storeBook($book);

echo json_encode([
    "success" => $result ? true : false,
    "message" => $result ? "Libro aggiunto con successo" : "Errore nel salvataggio"
]);
