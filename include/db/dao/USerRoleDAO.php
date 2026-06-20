<?php

require_once("include/model/UserRole.php");
require_once("include/db/DAO.php");

class UserRoleDAO extends DAO {

    private PDOStatement $stmtGetRolesByUser;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtDeleteByUser;
    private PDOStatement $stmtDeleteSingle;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        $this->stmtGetRolesByUser = $this->conn->prepare(
            "SELECT * FROM UTENTE_RUOLO WHERE ID_UTENTE = ?;"
        );

        $this->stmtInsert = $this->conn->prepare(
            "INSERT INTO UTENTE_RUOLO (ID_UTENTE, ID_RUOLO) VALUES (?, ?);"
        );

        $this->stmtDeleteByUser = $this->conn->prepare(
            "DELETE FROM UTENTE_RUOLO WHERE ID_UTENTE = ?;"
        );

        $this->stmtDeleteSingle = $this->conn->prepare(
            "DELETE FROM UTENTE_RUOLO WHERE ID_UTENTE = ? AND ID_RUOLO = ?;"
        );
    }

    public function getRolesByUser(int $userId): array {
        $this->stmtGetRolesByUser->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtGetRolesByUser->execute();

        $result = [];

        while ($rs = $this->stmtGetRolesByUser->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createUserRole($rs);
        }

        return $result;
    }

    public function addRoleToUser(int $userId, int $roleId): bool {
        $this->stmtInsert->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtInsert->bindValue(2, $roleId, PDO::PARAM_INT);
        return $this->stmtInsert->execute();
    }

    public function removeAllRolesFromUser(int $userId): bool {
        $this->stmtDeleteByUser->bindValue(1, $userId, PDO::PARAM_INT);
        return $this->stmtDeleteByUser->execute();
    }

    public function removeRoleFromUser(int $userId, int $roleId): bool {
        $this->stmtDeleteSingle->bindValue(1, $userId, PDO::PARAM_INT);
        $this->stmtDeleteSingle->bindValue(2, $roleId, PDO::PARAM_INT);
        return $this->stmtDeleteSingle->execute();
    }

    private function createUserRole(array $rs): UserRole {
        $ur = new UserRole();
        $ur->setUserId((int) $rs['ID_UTENTE']);
        $ur->setRoleId((int) $rs['ID_RUOLO']);
        return $ur;
    }

    public function createEntity(): UserRole {
        return new UserRole();
    }

    public function getGroupByUserId(int $userId): ?Role {
    // Prendo il record UTENTE_RUOLO
    $this->stmtGetRolesByUser->bindValue(1, $userId, PDO::PARAM_INT);
    $this->stmtGetRolesByUser->execute();

    $rs = $this->stmtGetRolesByUser->fetch(PDO::FETCH_ASSOC);
    if (!$rs) return null;

    // Estraggo ID_RUOLO
    $roleId = (int)$rs['ID_RUOLO'];

    // Uso RoleDAO per ottenere l’oggetto Role
    return $this->dataLayer->getRoleDAO()->getRoleById($roleId);
}

}
