<?php
header("Content-Type: application/json");

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

try {
    $dataLayer = new DataLayer(new DB_Connection());
    $categoryDAO = $dataLayer->getCategoryDAO();

    // Controllo POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["success" => false, "message" => "Metodo non consentito"]);
        exit;
    }

    if (!isset($_POST['category_id']) || !is_numeric($_POST['category_id'])) {
        echo json_encode(["success" => false, "message" => "ID categoria mancante o non valido"]);
        exit;
    }

    $categoryId = (int) $_POST['category_id'];

    // Controllo esistenza categoria (opzionale ma utile)
    if (method_exists($categoryDAO, 'getCategoryById')) {
        $existing = $categoryDAO->getCategoryById($categoryId);
        if (!$existing) {
            echo json_encode(["success" => false, "message" => "Categoria non trovata"]);
            exit;
        }
    }

    // Esegui la cancellazione: con ON DELETE CASCADE il DB rimuove libri e dipendenze
    $deleted = false;
    if (method_exists($categoryDAO, 'deleteCategory')) {
        $deleted = $categoryDAO->deleteCategory($categoryId);
    } else {
        // fallback: esegui delete diretto se il DAO non ha il metodo
        $conn = $dataLayer->getConnection(); // adatta se il tuo DataLayer espone la connessione
        $stmt = $conn->prepare("DELETE FROM CATEGORIA WHERE ID = ?");
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $deleted = $stmt->execute();
    }

    if ($deleted) {
        echo json_encode(["success" => true, "message" => "Categoria e libri correlati eliminati"]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore durante la cancellazione"]);
    }

} catch (PDOException $ex) {
    file_put_contents('error_log.txt', date('c') . " - PDOException elimina_categoria: " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Errore database"]);
} catch (Exception $ex) {
    file_put_contents('error_log.txt', date('c') . " - Exception elimina_categoria: " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Errore server"]);
}
