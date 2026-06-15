<?php
header("Content-Type: application/json");

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

try {
    $dataLayer = new DataLayer(new DB_Connection());
    $authorDAO = $dataLayer->getAuthorDAO();

    // Controllo POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["success" => false, "message" => "Metodo non consentito"]);
        exit;
    }

    if (!isset($_POST['author_id']) || !is_numeric($_POST['author_id'])) {
        echo json_encode(["success" => false, "message" => "ID autore mancante o non valido"]);
        exit;
    }

    $authorId = (int) $_POST['author_id'];

    // Controllo esistenza autore (opzionale ma utile)
    if (method_exists($authorDAO, 'getAuthorById')) {
        $existing = $authorDAO->getAuthorById($authorId);
        if (!$existing) {
            echo json_encode(["success" => false, "message" => "Autore non trovato"]);
            exit;
        }
    }

    // Esegui la cancellazione: con ON DELETE CASCADE il DB rimuove libri e dipendenze
    $deleted = false;
    if (method_exists($authorDAO, 'deleteAuthor')) {
        $deleted = $authorDAO->deleteAuthor($authorId);
    } else {
        // fallback: esegui delete diretto se il DAO non ha il metodo
        $conn = $dataLayer->getConnection(); // adatta se il tuo DataLayer espone la connessione
        $stmt = $conn->prepare("DELETE FROM AUTORE WHERE ID = ?");
        $stmt->bindValue(1, $authorId, PDO::PARAM_INT);
        $deleted = $stmt->execute();
    }

    if ($deleted) {
        echo json_encode(["success" => true, "message" => "Autore e libri correlati eliminati"]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore durante la cancellazione"]);
    }

} catch (PDOException $ex) {
    file_put_contents('error_log.txt', date('c') . " - PDOException elimina_autore: " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Errore database"]);
} catch (Exception $ex) {
    file_put_contents('error_log.txt', date('c') . " - Exception elimina_autore: " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Errore server"]);
}
