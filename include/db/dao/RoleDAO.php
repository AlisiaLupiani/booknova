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
        // Recupera tutti i ruoli
        $this->stmtGetAll = $this->conn->prepare("SELECT * FROM RUOLO;");

        // Recupera un singolo ruolo dall'ID
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM RUOLO WHERE ID = ?;");

        // Inserisce un nuovo ruolo (uso il nome colonna RUOLO per coerenza con la classe)
        $this->stmtInsert = $this->conn->prepare("INSERT INTO RUOLO (RUOLO) VALUES (?);");

        // Aggiorna un ruolo esistente
        $this->stmtUpdate = $this->conn->prepare("UPDATE RUOLO SET RUOLO = ? WHERE ID = ?;");

        // Elimina un ruolo
        $this->stmtDelete = $this->conn->prepare("DELETE FROM RUOLO WHERE ID = ?;");
    }

    /**
     * Prende tutti i ruoli
     */
    public function getAllRoles(): array {
        $this->stmtGetAll->execute();
        $result = [];
        while ($rs = $this->stmtGetAll->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createRole($rs);
        }
        return $result;
    }

    /**
     * Prende un ruolo dall'ID
     */
    public function getRoleById(int $id): ?Role {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createRole($rs) : null;
    }

    /**
     * Inserisce o aggiorna un ruolo
     */
    public function storeRole(Role $role): ?Role {
        if ($role->getId() !== null) {
            // Se l'ID esiste, aggiorniamo il record
            $this->stmtUpdate->bindValue(1, $role->getRuolo(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $role->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdate->execute()) return $role;
        } else {
            // Se l'ID è nullo, inseriamo un nuovo record
            $this->stmtInsert->bindValue(1, $role->getRuolo(), PDO::PARAM_STR);
            if ($this->stmtInsert->execute()) {
                $role->setId((int)$this->conn->lastInsertId());
                return $role;
            }
        }
        return null;
    }

    /**
     * Elimina un ruolo tramite ID
     */
    public function deleteRole(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    private function createRole(array $rs): Role {
        // Istanziamo il Proxy (Lazy Loading) come fatto nel CartDAO
        $role = new RoleProxy($this->dataLayer);
        $role->setId((int)$rs['ID']);
        $role->setRuolo($rs['RUOLO']);
        
        return $role;
    }
}