<?php

require_once("include/model/ShippingMethod.php");
require_once("include/db/DAO.php");

class ShippingMethodDAO extends DAO {

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
        // CORREZIONE: Uso dei nomi colonne minuscoli (nome, costo) come da SQL
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM METODO_SPEDIZIONE WHERE ID = ?;");
        $this->stmtGetAll = $this->conn->prepare("SELECT * FROM METODO_SPEDIZIONE ORDER BY costo ASC;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO METODO_SPEDIZIONE (nome, costo) VALUES (?, ?);");
        $this->stmtUpdate = $this->conn->prepare("UPDATE METODO_SPEDIZIONE SET nome = ?, costo = ? WHERE ID = ?;");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM METODO_SPEDIZIONE WHERE ID = ?;");
    }

    public function getShippingMethodById(int $id): ?ShippingMethod {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createShippingMethod($rs) : null;
    }

    public function getAllShippingMethods(): array {
        $this->stmtGetAll->execute();
        $result = [];
        while ($rs = $this->stmtGetAll->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createShippingMethod($rs);
        }
        return $result;
    }

    public function storeShippingMethod(ShippingMethod $sm): ?ShippingMethod {
        if ($sm->getId() !== null) {
            $this->stmtUpdate->bindValue(1, $sm->getName(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $sm->getCost(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(3, $sm->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdate->execute()) return $sm;
        } else {
            $this->stmtInsert->bindValue(1, $sm->getName(), PDO::PARAM_STR);
            $this->stmtInsert->bindValue(2, $sm->getCost(), PDO::PARAM_STR);
            if ($this->stmtInsert->execute()) {
                $sm->setId((int)$this->conn->lastInsertId());
                return $sm;
            }
        }
        return null;
    }

    public function deleteShippingMethod(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    private function createShippingMethod(array $rs): ShippingMethod {
        $sm = new ShippingMethod();
        $sm->setId((int)$rs['ID']);
        // CORREZIONE: Accesso all'array con chiavi minuscole come da DB
        $sm->setName($rs['nome']); 
        $sm->setCost((float)$rs['costo']);
        return $sm;
    }
}