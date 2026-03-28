<?php

require_once("include/model/Publisher.php");
require_once("include/db/DAO.php");

class PublisherDAO extends DAO {

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
        // Query basate sulla tabella EDITORE del tuo SQL
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM EDITORE WHERE ID = ?;");
        $this->stmtGetAll = $this->conn->prepare("SELECT * FROM EDITORE ORDER BY NOME ASC;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO EDITORE (NOME) VALUES (?);");
        $this->stmtUpdate = $this->conn->prepare("UPDATE EDITORE SET NOME = ? WHERE ID = ?;");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM EDITORE WHERE ID = ?;");
    }

    public function getPublisherById(int $id): ?Publisher {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createPublisher($rs) : null;
    }

    public function getAllPublishers(): array {
        $this->stmtGetAll->execute();
        $result = [];
        while ($rs = $this->stmtGetAll->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createPublisher($rs);
        }
        return $result;
    }

    public function storePublisher(Publisher $publisher): ?Publisher {
        if ($publisher->getId() !== null) {
            $this->stmtUpdate->bindValue(1, $publisher->getName(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $publisher->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdate->execute()) return $publisher;
        } else {
            $this->stmtInsert->bindValue(1, $publisher->getName(), PDO::PARAM_STR);
            if ($this->stmtInsert->execute()) {
                $publisher->setId((int)$this->conn->lastInsertId());
                return $publisher;
            }
        }
        return null;
    }

    public function deletePublisher(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    private function createPublisher(array $rs): Publisher {
        $publisher = new Publisher();
        $publisher->setId((int)$rs['ID']);
        $publisher->setName($rs['NOME']);
        return $publisher;
    }
}