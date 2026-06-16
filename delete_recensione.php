<?php
header("Content-Type: application/json");

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

// Controllo POST
if (!isset($_POST["review_id"]) || !is_numeric($_POST["review_id"])) {
    echo json_encode(["success" => false, "message" => "ID recensione non valido"]);
    exit;
}

$review_id = (int)$_POST["review_id"];

$dataLayer = new DataLayer(new DB_Connection());
$reviewDAO = $dataLayer->getReviewDAO();

// Cancella recensione
if ($reviewDAO->deleteReview($review_id)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Errore durante l'eliminazione"]);
}
