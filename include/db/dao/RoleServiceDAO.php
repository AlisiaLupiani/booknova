<?php

require_once("include/model/RoleService.php");
require_once("include/db/DAO.php");

class RoleServiceDAO extends DAO {

    private PDOStatement $stmtGetServicesByRole;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtDeleteByRole;
    private PDOStatement $stmtDeleteSingle;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        $this->stmtGetServicesByRole = $this->conn->prepare(
            "SELECT * FROM RUOLO_SERVIZIO WHERE ID_RUOLO = ?;"
        );

        $this->stmtInsert = $this->conn->prepare(
            "INSERT INTO RUOLO_SERVIZIO (ID_RUOLO, ID_SERVIZIO) VALUES (?, ?);"
        );

        $this->stmtDeleteByRole = $this->conn->prepare(
            "DELETE FROM RUOLO_SERVIZIO WHERE ID_RUOLO = ?;"
        );

        $this->stmtDeleteSingle = $this->conn->prepare(
            "DELETE FROM RUOLO_SERVIZIO WHERE ID_RUOLO = ? AND ID_SERVIZIO = ?;"
        );
    }

    public function getServicesByRole(int $roleId): array {
        $this->stmtGetServicesByRole->bindValue(1, $roleId, PDO::PARAM_INT);
        $this->stmtGetServicesByRole->execute();

        $result = [];

        while ($rs = $this->stmtGetServicesByRole->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createRoleService($rs);
        }

        return $result;
    }

    public function addServiceToRole(int $roleId, int $serviceId): bool {
        $this->stmtInsert->bindValue(1, $roleId, PDO::PARAM_INT);
        $this->stmtInsert->bindValue(2, $serviceId, PDO::PARAM_INT);
        return $this->stmtInsert->execute();
    }

    public function removeAllServicesFromRole(int $roleId): bool {
        $this->stmtDeleteByRole->bindValue(1, $roleId, PDO::PARAM_INT);
        return $this->stmtDeleteByRole->execute();
    }

    public function removeServiceFromRole(int $roleId, int $serviceId): bool {
        $this->stmtDeleteSingle->bindValue(1, $roleId, PDO::PARAM_INT);
        $this->stmtDeleteSingle->bindValue(2, $serviceId, PDO::PARAM_INT);
        return $this->stmtDeleteSingle->execute();
    }

    private function createRoleService(array $rs): RoleService {
        $rsObj = new RoleService();
        $rsObj->setRoleId((int) $rs['ID_RUOLO']);
        $rsObj->setServiceId((int) $rs['ID_SERVIZIO']);
        return $rsObj;
    }

    public function createEntity(): RoleService {
        return new RoleService();
    }
}
