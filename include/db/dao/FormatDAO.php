<?php

require_once("include/model/Format.php");
require_once("include/model/proxy/FormatProxy.php");
require_once("include/db/DAO.php");

class FormatDAO extends DAO {

    private PDOStatement $stmtGetFormatById;
    private PDOStatement $stmtGetAllFormats;
    private PDOStatement $stmtInsertFormat;
    private PDOStatement $stmtUpdateFormat;
    private PDOStatement $stmtDeleteFormat;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        
        $this->stmtGetFormatById = $this->conn->prepare("SELECT * FROM FORMATO WHERE ID = ?;");

        $this->stmtGetAllFormats = $this->conn->prepare("SELECT * FROM FORMATO;");
        
        $this->stmtInsertFormat = $this->conn->prepare("INSERT INTO FORMATO (FORMATO) VALUES (?);");

        $this->stmtUpdateFormat = $this->conn->prepare("UPDATE FORMATO SET FORMATO = ? WHERE ID = ?;");

        $this->stmtDeleteFormat = $this->conn->prepare("DELETE FROM FORMATO WHERE ID = ?;");

    }

    public function getFormatById(int $id): ?Format {
        $this->stmtGetFormatById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetFormatById->execute();
        $rs = $this->stmtGetFormatById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createFormat($rs) : null;
    }

    public function getAllFormats(): array {
        $this->stmtGetAllFormats->execute();
        $result = [];
        while ($rs = $this->stmtGetAllFormats->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createFormat($rs);
        }
        return $result;
    }

    public function storeFormat(Format $format): ?Format {
        if ($format->getId() !== null) {
            
            $this->stmtUpdateFormat->bindValue(1, $format->getFormat(), PDO::PARAM_STR);
            $this->stmtUpdateFormat->bindValue(2, $format->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdateFormat->execute()) return $format;
        } else {
            
            $this->stmtInsertFormat->bindValue(1, $format->getFormat(), PDO::PARAM_STR);
            if ($this->stmtInsertFormat->execute()) {
                $format->setId((int)$this->conn->lastInsertId());
                return $format;
            }
        }
        return null;
    }

    public function deleteFormat(int $id): bool {
        $this->stmtDeleteFormat->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDeleteFormat->execute();
    }

    private function createFormat(array $rs): Format {
        $format = new Format();
        $format->setId((int)$rs['ID']);
        $format->setFormat($rs['FORMATO']);
        return $format;
    }
}