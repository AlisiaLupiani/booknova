<?php

require_once("include/model/Category.php");
require_once("include/db/DAO.php");

class CategoryDAO extends DAO {

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
        // Query basate sulla tabella Categoria del tuo DB BOOKNOVA
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM Categoria WHERE ID = ?;");
        $this->stmtGetAll = $this->conn->prepare("SELECT * FROM Categoria ORDER BY NOME ASC;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO Categoria (NOME) VALUES (?);");
        $this->stmtUpdate = $this->conn->prepare("UPDATE Categoria SET NOME = ? WHERE ID = ?;");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM Categoria WHERE ID = ?;");
    }

    // Recupera una categoria per ID
    public function getCategoryById(int $id): ?Category {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createCategory($rs) : null;
    }

    // Recupera tutte le categorie (utile per i filtri di ricerca)
    public function getAllCategories(): array {
        $this->stmtGetAll->execute();
        $result = [];
        while ($rs = $this->stmtGetAll->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createCategory($rs);
        }
        return $result;
    }

    // Salva o aggiorna una categoria
    public function storeCategory(Category $category): ?Category {
        if ($category->getId() !== null) {
            // UPDATE
            $this->stmtUpdate->bindValue(1, $category->getName(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $category->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdate->execute()) return $category;
        } else {
            // INSERT
            $this->stmtInsert->bindValue(1, $category->getName(), PDO::PARAM_STR);
            if ($this->stmtInsert->execute()) {
                $category->setId((int)$this->conn->lastInsertId());
                return $category;
            }
        }
        return null;
    }

    // Cancella una categoria
    public function deleteCategory(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    // Mappatura da database a oggetto PHP
    private function createCategory(array $rs): Category {
        $category = new Category();
        $category->setId((int)$rs['ID']);
        $category->setName($rs['NOME']);
        return $category;
    }
}