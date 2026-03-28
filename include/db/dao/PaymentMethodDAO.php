<?php

require_once("include/model/PaymentMethod.php");
require_once("include/db/DAO.php");

class PaymentMethodDAO extends DAO {

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
        // Query basate sulla tabella METODO_PAGAMENTO del database BOOKNOVA
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM METODO_PAGAMENTO WHERE ID = ?;");
        $this->stmtGetAll = $this->conn->prepare("SELECT * FROM METODO_PAGAMENTO;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO METODO_PAGAMENTO (NOME) VALUES (?);");
        $this->stmtUpdate = $this->conn->prepare("UPDATE METODO_PAGAMENTO SET NOME = ? WHERE ID = ?;");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM METODO_PAGAMENTO WHERE ID = ?;");
    }

    public function getPaymentMethodById(int $id): ?PaymentMethod {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createPaymentMethod($rs) : null;
    }

    public function getAllPaymentMethods(): array {
        $this->stmtGetAll->execute();
        $result = [];
        while ($rs = $this->stmtGetAll->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createPaymentMethod($rs);
        }
        return $result;
    }

    public function storePaymentMethod(PaymentMethod $pm): ?PaymentMethod {
        if ($pm->getId() !== null) {
            // Aggiornamento
            $this->stmtUpdate->bindValue(1, $pm->getName(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $pm->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdate->execute()) return $pm;
        } else {
            // Inserimento
            $this->stmtInsert->bindValue(1, $pm->getName(), PDO::PARAM_STR);
            if ($this->stmtInsert->execute()) {
                $pm->setId((int)$this->conn->lastInsertId());
                return $pm;
            }
        }
        return null;
    }

    public function deletePaymentMethod(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    private function createPaymentMethod(array $rs): PaymentMethod {
        $pm = new PaymentMethod();
        $pm->setId((int)$rs['ID']);
        $pm->setName($rs['NOME']);
        return $pm;
    }
}