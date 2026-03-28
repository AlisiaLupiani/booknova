<?php

require_once("include/model/Review.php");
require_once("include/model/proxy/ReviewProxy.php");
require_once("include/db/DAO.php");

class ReviewDAO extends DAO {

    private PDOStatement $stmtGetById;
    private PDOStatement $stmtGetByBook;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtUpdate;
    private PDOStatement $stmtDelete;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        // Query basate sulla tabella RECENSIONE del database BOOKNOVA
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM RECENSIONE WHERE ID = ?;");
        $this->stmtGetByBook = $this->conn->prepare("SELECT * FROM RECENSIONE WHERE ID_LIBRO = ? ORDER BY DATA DESC;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO RECENSIONE (ID_UTENTE, ID_LIBRO, TESTO, DATA) VALUES (?, ?, ?, ?);");
        $this->stmtUpdate = $this->conn->prepare("UPDATE RECENSIONE SET TESTO = ?, DATA = ? WHERE ID = ?;");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM RECENSIONE WHERE ID = ?;");
    }

    public function getReviewById(int $id): ?Review {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createReview($rs) : null;
    }

    /**
     * Recupera tutte le recensioni associate a un libro specifico
     */
    public function getReviewsByBook(int $bookId): array {
        $this->stmtGetByBook->bindValue(1, $bookId, PDO::PARAM_INT);
        $this->stmtGetByBook->execute();
        $result = [];
        while ($rs = $this->stmtGetByBook->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createReview($rs);
        }
        return $result;
    }

    public function storeReview(Review $review): ?Review {
        if ($review->getId() !== null) {
            // Aggiornamento (UPDATE)
            $this->stmtUpdate->bindValue(1, $review->getContent(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(2, $review->getDate(), PDO::PARAM_STR);
            $this->stmtUpdate->bindValue(3, $review->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdate->execute()) return $review;
        } else {
            // Inserimento (INSERT)
            $idUser = $review->getUser() ? $review->getUser()->getId() : null;
            $idBook = $review->getBook() ? $review->getBook()->getId() : null;

            $this->stmtInsert->bindValue(1, $idUser, PDO::PARAM_INT);
            $this->stmtInsert->bindValue(2, $idBook, PDO::PARAM_INT);
            $this->stmtInsert->bindValue(3, $review->getContent(), PDO::PARAM_STR);
            $this->stmtInsert->bindValue(4, $review->getDate(), PDO::PARAM_STR);

            if ($this->stmtInsert->execute()) {
                $review->setId((int)$this->conn->lastInsertId());
                return $review;
            }
        }
        return null;
    }

    public function deleteReview(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    private function createReview(array $rs): Review {
        // Caricamento tramite Proxy per gestire le relazioni con User e Book
        $review = new ReviewProxy($this->dataLayer);
        $review->setId((int)$rs['ID']);
        $review->setContent($rs['TESTO']); // Mapping colonna TESTO -> proprietà content
        $review->setDate($rs['DATA']);
        
        // Passiamo gli ID al Proxy per il Lazy Loading
        $review->setUserId((int)$rs['ID_UTENTE']);
        $review->setBookId((int)$rs['ID_LIBRO']);
        
        return $review;
    }
}