<?php

require_once("include/model/Book.php");
require_once("include/model/proxy/BookProxy.php");
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

        return $rs ? $this->creatBook($rs) : null;
    }

    public function getAllBooks(): array {
        $this->stmtGetAllBooks->execute();
        $result = [];
        while ($rs = $this->stmtGetAllBooks->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createBook($rs);
        }
        return $result;
    }

    public function storeBook(Book $book): ?Book {
        if ($book->getId() !== null) {
            // Logica UPDATE
            $this->stmtUpdateBook->bindValue(1, $book->getTitle(), PDO::PARAM_STR);
            $this->stmtUpdateBook->bindValue(2, $book->getPrice(), PDO::PARAM_STR);
            $this->stmtUpdateBook->bindValue(3, $book->getDescription(), PDO::PARAM_STR);
            $this->stmtUpdateBook->bindValue(4, $book->getAuthor() ? $book->getAuthor()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(5, $book->getPublisher() ? $book->getPublisher()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(6, $book->getCategory() ? $book->getCategory()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(7, $book->getFormat() ? $book->getFormat()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(8, $book->getCondition() ? $book->getCondition()->getId() : null, PDO::PARAM_INT);
            $this->stmtUpdateBook->bindValue(9, $book->getId(), PDO::PARAM_INT);
            
            if($this->stmtUpdateBook->execute()) return $book;
        } else {
            // Logica INSERT
            $this->stmtInsertBook->bindValue(1, $book->getTitle(), PDO::PARAM_STR);
            $this->stmtInsertBook->bindValue(2, $book->getPrice(), PDO::PARAM_STR);
            $this->stmtInsertBook->bindValue(3, $book->getDescription(), PDO::PARAM_STR);
            $this->stmtInsertBook->bindValue(4, $book->getAuthor() ? $book->getAuthor()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsertBook->bindValue(5, $book->getPublisher() ? $book->getPublisher()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsertBook->bindValue(6, $book->getCategory() ? $book->getCategory()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsertBook->bindValue(7, $book->getFormat() ? $book->getFormat()->getId() : null, PDO::PARAM_INT);
            $this->stmtInsertBook->bindValue(8, $book->getCondition() ? $book->getCondition()->getId() : null, PDO::PARAM_INT);
            
            if($this->stmtInsertBook->execute()){
                $book->setId($this->conn->lastInsertId());
                return $book;
            }
        }
        return null;
    }

    public function getBooksByCategory(int $categoryId): array {
        $this->stmtGetBooksByCategory->bindValue(1, $categoryId, PDO::PARAM_INT);
        $this->stmtGetBooksByCategory->execute();
        $result = [];
        while ($rs = $this->stmtGetBooksByCategory->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createBook($rs);
        }
        return $result;
    }

    private function createBook(array $rs): Book {
        $book = new BookProxy($this->dataLayer);
        $book->setId($rs['ID']);
        $book->setTitle($rs['TITOLO']);
        $book->setPrice((float)$rs['PREZZO']);
        $book->setDescription($rs['DESCRIZIONE']);
        
        // Impostiamo gli ID nel Proxy (Lazy Loading)
        $book->setAuthorId($rs['ID_AUTORE']);
        $book->setPublisherId($rs['ID_EDITORE']);
        $book->setCategoryId($rs['ID_CATEGORIA']);
        $book->setFormatId($rs['ID_FORMATO']);
        $book->setConditionId($rs['ID_CONDIZIONE']);
        
        return $book;
    }

}