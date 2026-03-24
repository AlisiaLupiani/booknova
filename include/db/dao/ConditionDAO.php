<?php

require_once("include/model/Condition.php");
require_once("include/db/DAO.php");

class ConditionDAO extends DAO {

    private PDOStatement $stmtGetById;
    private PDOStatement $stmtGetAll;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtUpdate;
    private PDOStatement $stmtDelete;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        // Lettura
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM CONDIZIONE WHERE ID = ?;");
        $this->stmtGetAll = $this->conn->prepare("SELECT * FROM CONDIZIONE;");
        
        // Scrittura
        $this->stmtInsert = $this->conn->prepare("INSERT INTO CONDIZIONE (DESCRIZIONE) VALUES (?);");
        $this->stmtUpdate = $this->conn->prepare("UPDATE CONDIZIONE SET DESCRIZIONE = ? WHERE ID = ?;");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM CONDIZIONE WHERE ID = ?;");
    }

    public function getConditionById(int $id): ?Condition {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createCondition($rs) : null;
    }

    public function getAllConditions(): array {
        $this->stmtGetAll->execute();
        $result = [];
        while ($rs = $this->stmtGetAll->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createCondition($rs);
        }
        return $result;
    }

    public function storeCondition(Condition $condition): ?Condition {
        if ($condition->getId() !== null) {
            // UPDATE
            $this->stmtUpdate->bindValue(1, $condition->getCondition(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $condition->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdate->execute()) return $condition;
        } else {
            // INSERT
            $this->stmtInsert->bindValue(1, $condition->getCondition(), PDO::PARAM_STR);
            if ($this->stmtInsert->execute()) {
                $condition->setId((int)$this->conn->lastInsertId());
                return $condition;
            }
        }
        return null;
    }

    public function deleteCondition(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    private function createCondition(array $rs): Condition {
        $condition = new Condition();
        $condition->setId((int)$rs['ID']);
        // Mappiamo la colonna DESCRIZIONE del DB sulla proprietà $condition della classe
        $condition->setCondition($rs['DESCRIZIONE']);
        return $condition;
    }
}