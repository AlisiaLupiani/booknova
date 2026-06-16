<?php
header("Content-Type: application/json");

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Metodo non valido"]);
    exit;
}

$nome = trim($_POST["userName"] ?? "");
$messaggio = trim($_POST["comment"] ?? "");

if ($nome === "" || $messaggio === "") {
    echo json_encode(["success" => false, "message" => "Compila tutti i campi"]);
    exit;
}

try {
    $dataLayer = new DataLayer(new DB_Connection());
    $conn = $dataLayer->getConnection();

    $stmt = $conn->prepare("
        INSERT INTO RECENSIONE_SITO (NOME, TESTO)
        VALUES (?, ?)
    ");

    // Cognome e voto non presenti → valori di default
    $stmt->execute([$nome, $messaggio]);

    echo json_encode(["success" => true, "message" => "Recensione inviata con successo!"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Errore server"]);
}
