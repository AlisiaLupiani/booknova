<?php

require_once("include/model/Libro.php");
require_once("include/model/proxy/LibroProxy.php");
require_once("include/db/DAO.php");


class BookDAO extends DAO {

    private PDOStatement $stmtGetBookById;
    private PDOStatement $stmtGetAllBooks;
    private PDOStatement $stmtInsertBook;
    private PDOStatement $stmtUpdateBook;
    private PDOStatement $stmtDeleteBook;
    private PDOStatement $stmtGetBooksByCategory;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        // Query di lettura
        $this->stmtGetBookById = $this->conn->prepare("SELECT * FROM LIBRO WHERE ID = ?;");
        $this->stmtGetAllBooks = $this->conn->prepare("SELECT * FROM LIBRO;");
        
        // Query di inserimento (rispetta le tue colonne SQL)
        $this->stmtInsertBook = $this->conn->prepare("INSERT INTO LIBRO (TITOLO, PREZZO, DESCRIZIONE, ID_AUTORE, ID_EDITORE, ID_CATEGORIA, ID_FORMATO, ID_CONDIZIONE) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
        
        // Query di aggiornamento
        $this->stmtUpdateBook = $this->conn->prepare("UPDATE LIBRO SET TITOLO = ?, PREZZO = ?, DESCRIZIONE = ?, ID_AUTORE = ?, ID_EDITORE = ?, ID_CATEGORIA = ?, ID_FORMATO = ?, ID_CONDIZIONE = ? WHERE ID = ?;");
        
        // Query di cancellazione
        $this->stmtDeleteBook = $this->conn->prepare("DELETE FROM LIBRO WHERE ID = ?;");
        
        // Query per ottenere i libri per categoria
        $this->stmtGetBooksByCategory = $this->conn->prepare("SELECT * FROM LIBRO WHERE ID_CATEGORIA = ?;");
    }

    public function getBookById(int $id): ?Libro {
        $this->stmtGetBookById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetBookById->execute();
        $rs = $this->stmtGetBookById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createLibro($rs) : null;
    }

    public function getAllBooks(): array {
        $this->stmtGetAllBooks->execute();
        $result = [];
        while ($rs = $this->stmtGetAllBooks->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createLibro($rs);
        }
        return $result;
    }

    public function storeLibro(Libro $libro): ?Libro {
        if ($libro->getId() !== null) {
            // Logica UPDATE
            $this->stmtUpdateBook->bindValue(1, $libro->getTitle(), PDO::PARAM_STR);
            $this->stmtUpdateBook->bindValue(2, $libro->getPrice(), PDO::PARAM_STR);
            $this->stmtUpdateBook->bindValue(3, $libro->getDescription(), PDO::PARAM_STR);
            $this->stmtUpdateBook->bindValue(4, $libro->getAuthor() ? $libro->getAuthor()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(5, $libro->getPublisher() ? $libro->getPublisher()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(6, $libro->getCategory() ? $libro->getCategory()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(7, $libro->getFormat() ? $libro->getFormat()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(8, $libro->getCondition() ? $libro->getCondition()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(9, $libro->getId(), PDO::PARAM_INT);
            
            if($this->stmtUpdateBook->execute()) return $libro;
        } else {
            // Logica INSERT
            $this->stmtInsertBook->bindValue(1, $libro->getTitle(), PDO::PARAM_STR);
            $this->stmtInsertBook->bindValue(2, $libro->getPrice(), PDO::PARAM_STR);
            $this->stmtInsertBook->bindValue(3, $libro->getDescription(), PDO::PARAM_STR);
            $this->stmtInsertBook->bindValue(4, $libro->getAuthor() ? $libro->getAuthor()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsertBook->bindValue(5, $libro->getPublisher() ? $libro->getPublisher()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsertBook->bindValue(6, $libro->getCategory() ? $libro->getCategory()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsertBook->bindValue(7, $libro->getFormat() ? $libro->getFormat()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsertBook->bindValue(8, $libro->getCondition() ? $libro->getCondition()->getId() : null, PDO::PARAM_INT);
            
            if($this->stmtInsertBook->execute()){
                $libro->setId($this->conn->lastInsertId());
                return $libro;
            }
        }
        return null;
    }

    public function getBooksByCategory(int $categoryId): array {
        $this->stmtGetBooksByCategory->bindValue(1, $categoryId, PDO::PARAM_INT);
        $this->stmtGetBooksByCategory->execute();
        $result = [];
        while ($rs = $this->stmtGetBooksByCategory->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createLibro($rs);
        }
        return $result;
    }

    private function createLibro(array $rs): Libro {
        $libro = new LibroProxy($this->dataLayer);
        $libro->setId($rs['ID']);
        $libro->setTitle($rs['TITOLO']);
        $libro->setPrice((float)$rs['PREZZO']);
        $libro->setDescription($rs['DESCRIZIONE']);
        
        // Impostiamo gli ID nel Proxy (Lazy Loading)
        $libro->setAuthorId($rs['ID_AUTORE']);
        $libro->setPublisherId($rs['ID_EDITORE']);
        $libro->setCategoryId($rs['ID_CATEGORIA']);
        $libro->setFormatId($rs['ID_FORMATO']);
        $libro->setConditionId($rs['ID_CONDIZIONE']);
        
        return $libro;
    }

}