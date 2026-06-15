<?php
header("Content-Type: application/json; charset=utf-8");

require_once("include/db/DB_Connection.php");
require_once("include/db/DataLayer.php");
require_once("include/model/User.php");
require_once("include/model/proxy/UserProxy.php");

try {
    // Inizializza DataLayer e DAO
    $dataLayer = new DataLayer(new DB_Connection());
    $userDAO = $dataLayer->getUserDAO();

    // Verifica metodo HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["success" => false, "message" => "Metodo non consentito"]);
        exit;
    }

    // Validazione input
    if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
        echo json_encode(["success" => false, "message" => "ID utente mancante o non valido"]);
        exit;
    }

    $userId = (int) $_POST['user_id'];

    // Verifica che il DAO esponga getUserById
    if (!method_exists($userDAO, 'getUserById')) {
        echo json_encode(["success" => false, "message" => "DAO non compatibile: manca getUserById"]);
        exit;
    }

    // Recupera l'oggetto User dal DAO
    $userObj = $userDAO->getUserById($userId);
    if (!$userObj) {
        echo json_encode(["success" => false, "message" => "Utente non trovato"]);
        exit;
    }

    // Verifica che deleteUser esista e accetti un oggetto User
    if (!method_exists($userDAO, 'deleteUser')) {
        echo json_encode(["success" => false, "message" => "DAO non compatibile: manca deleteUser"]);
        exit;
    }

    // Reflection per controllare la firma e assicurarsi che non si stia passando un int
    $ref = new ReflectionMethod($userDAO, 'deleteUser');
    $params = $ref->getParameters();
    if (count($params) > 0) {
        $firstParam = $params[0];
        $type = $firstParam->hasType() ? $firstParam->getType()->getName() : null;
        if ($type !== null && (strtolower($type) === 'int' || strtolower($type) === 'integer')) {
            echo json_encode([
                "success" => false,
                "message" => "Il metodo deleteUser del DAO accetta un id (int). Questo endpoint richiede che deleteUser accetti un oggetto User."
            ]);
            exit;
        }
    }

    // Chiamiamo deleteUser passando l'oggetto User
    try {
        $deleted = $userDAO->deleteUser($userObj);
    } catch (TypeError $te) {
        file_put_contents('error_log.txt', date('c') . " - TypeError deleteUser: " . $te->getMessage() . PHP_EOL, FILE_APPEND);
        echo json_encode(["success" => false, "message" => "Il DAO non accetta l'oggetto User come parametro."]);
        exit;
    }

    if ($deleted) {
        echo json_encode(["success" => true, "message" => "Utente eliminato con successo"]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore durante la cancellazione"]);
    }

} catch (PDOException $ex) {
    file_put_contents('error_log.txt', date('c') . " - PDOException elimina_utente: " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Errore database"]);
} catch (Exception $ex) {
    file_put_contents('error_log.txt', date('c') . " - Exception elimina_utente: " . $ex->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Errore server"]);
}
