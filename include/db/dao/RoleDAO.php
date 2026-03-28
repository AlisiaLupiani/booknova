<?php

require_once("include/model/Role.php");
require_once("include/model/proxy/RoleProxy.php");
require_once("include/db/DAO.php");

class RoleDAO extends DAO {

    private PDOStatement $stmtGetAll;
    private PDOStatement $stmtGetById;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtUpdate;
    private PDOStatement $stmtDelete;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        // Query basate sulla tabella RUOLO: ID, RUOLO
        $this->stmtGetAll = $this->conn->prepare("SELECT * FROM RUOLO;");
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM RUOLO WHERE ID = ?;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO RUOLO (RUOLO) VALUES (?);");
        $this->stmtUpdate = $this->conn->prepare("UPDATE RUOLO SET RUOLO = ? WHERE ID = ?;");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM RUOLO WHERE ID = ?;");
    }

    public function getAllRoles(): array {
        $this->stmtGetAll->execute();
        $result = [];
        while ($rs = $this->stmtGetAll->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createRole($rs);
        }
        return $result;
    }

    public function getRoleById(int $id): ?Role {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createRole($rs) : null;
    }

    public function storeRole(Role $role): ?Role {
        if ($role->getId() !== null) {
            // UPDATE
            $this->stmtUpdate->bindValue(1, $role->getRuolo(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $role->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdate->execute()) return $role;
        } else {
            // INSERT
            $this->stmtInsert->bindValue(1, $role->getRuolo(), PDO::PARAM_STR);
            if ($this->stmtInsert->execute()) {
                $role->setId((int)$this->conn->lastInsertId());
                return $role;
            }
        }
        return null;
    }

    public function deleteRole(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    private function createRole(array $rs): Role {
        // Usiamo il Proxy per coerenza con ReviewDAO
        $role = new RoleProxy($this->dataLayer);
        $role->setId((int)$rs['ID']);
        $role->setRuolo($rs['RUOLO']);
        
        return $role;
    }
}