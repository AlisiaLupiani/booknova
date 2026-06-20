<?php

class PermissionProxy {

    private ?DataLayer $dataLayer;

    public function __construct(?DataLayer $dataLayer) {
        $this->dataLayer = $dataLayer;
    }

    public function userHasPermission(int $userId, string $serviceName): bool {

        $sql = "
            SELECT COUNT(*) AS CNT
            FROM UTENTE_RUOLO ur
            JOIN RUOLO_SERVIZIO rs ON ur.ID_RUOLO = rs.ID_RUOLO
            JOIN SERVIZIO s ON rs.ID_SERVIZIO = s.ID
            WHERE ur.ID_UTENTE = ?
            AND s.NOME = ?
        ";

        $stmt = $this->dataLayer->getConnection()->prepare($sql);
        $stmt->execute([$userId, $serviceName]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['CNT'] > 0;
    }

    public function checkPermission(string $serviceName): void {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }

        if (!$this->userHasPermission($_SESSION['user_id'], $serviceName)) {
            die("Access denied");
        }
    }
}
