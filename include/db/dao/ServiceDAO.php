<?php

require_once("include/model/Service.php");
require_once("include/db/DAO.php");

class ServiceDAO extends DAO {

    private PDOStatement $stmtGetById;
    private PDOStatement $stmtGetAll;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtUpdate;
    private PDOStatement $stmtDelete;
    private PDOStatement $stmtAllService;


    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM SERVIZIO WHERE ID = ?;");
        $this->stmtGetAll = $this->conn->prepare("SELECT * FROM SERVIZIO;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO SERVIZIO (NOME) VALUES (?);");
        $this->stmtUpdate = $this->conn->prepare("UPDATE SERVIZIO SET NOME = ? WHERE ID = ?;");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM SERVIZIO WHERE ID = ?;");
    }

    public function getById(int $id): ?Service {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();

        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);
        return $rs ? $this->createService($rs) : null;
    }

    public function getAll(): array {
        $this->stmtGetAll->execute();
        $result = [];

        while ($rs = $this->stmtGetAll->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createService($rs);
        }

        return $result;
    }

    public function store(Service $service): ?Service {
        if ($service->getId() !== null) {
            $this->stmtUpdate->bindValue(1, $service->getName(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $service->getId(), PDO::PARAM_INT);

            if ($this->stmtUpdate->execute()) {
                return $service;
            }
        } else {
            $this->stmtInsert->bindValue(1, $service->getName(), PDO::PARAM_STR);

            if ($this->stmtInsert->execute()) {
                $service->setId((int) $this->conn->lastInsertId());
                return $service;
            }
        }

        return null;
    }

    public function delete(Service $service): bool {
        $this->stmtDelete->bindValue(1, $service->getId(), PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    private function createService(array $rs): Service {
        $service = new Service();
        $service->setId((int) $rs['ID']);
        $service->setName($rs['NOME']);
        return $service;
    }

    public function createEntity(): Service {
        return new Service();
    }
    public function getAllServices(): array {
        $this->stmtGetAll->execute();
        $result = [];

        while ($rs = $this->stmtGetAll->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createService($rs);
        }

        return $result;
    }

   
}
