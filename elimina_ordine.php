<?php
header("Content-Type: application/json");

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");

try {
    $dataLayer = new DataLayer(new DB_Connection());
    $orderDAO = $dataLayer->getOrderDAO();

    // Controllo POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["success" => false, "message" => "Metodo non consentito"]);
        exit;
    }

    if (!isset($_POST['order_id']) || !is_numeric($_POST['order_id'])) {
        echo json_encode(["success" => false, "message" => "ID ordine mancante o non valido"]);
        exit;
    }

    $orderId = (int) $_POST['order_id'];

    // Controllo esistenza ordine (opzionale ma utile)
    if (method_exists($orderDAO, 'getOrderById')) {
        $existing = $orderDAO->getOrderById($orderId);
        if (!$existing) {
            echo json_encode(["success" => false, "message" => "Ordine non trovato"]);
            exit;
        }
    }

    // Esegui la cancellazione: con ON DELETE CASCADE il DB rimuove libri e dipendenze
    $deleted = false;
    if (method_exists($orderDAO, 'deleteOrder')) {
        $deleted = $orderDAO->deleteOrder($orderId);
    } else {
        // fallback: esegui delete diretto se il DAO non ha il metodo
        $conn = $dataLayer->getConnection(); // adatta se il tuo DataLayer espone la connessione
        $stmt = $conn->prepare("DELETE FROM ORDINE WHERE ID = ?");
        $stmt->bindValue(1, $orderId, PDO::PARAM_INT);
        $deleted = $stmt->execute();
    }

    if ($deleted) {
        echo json_encode(["success" => true, "message" => "Ordine e libri correlati eliminati"]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore durante la cancellazione"]);
    }

} catch (PDOException $ex) {
    file_put_contents('error_log.txt', date('c') . " - PDOException elimina_ordine: " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Errore database"]);
} catch (Exception $ex) {
    file_put_contents('error_log.txt', date('c') . " - Exception elimina_ordine: " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Errore server"]);
}
