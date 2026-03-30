<?php

require_once("include/model/Rating.php");
require_once("include/model/proxy/RatingProxy.php");
require_once("include/db/DAO.php");

class RatingDAO extends DAO {

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
        // Tabella VOTO: ID, ID_UTENTE, ID_LIBRO, VALORE, DATA
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM VOTO WHERE ID = ?;");
        $this->stmtGetByBook = $this->conn->prepare("SELECT * FROM VOTO WHERE ID_LIBRO = ?;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO VOTO (ID_UTENTE, ID_LIBRO, VALORE, DATA) VALUES (?, ?, ?, ?);");
        $this->stmtUpdate = $this->conn->prepare("UPDATE VOTO SET VALORE = ?, DATA = ? WHERE ID = ?;");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM VOTO WHERE ID = ?;");
    }

    public function getRatingById(int $id): ?Rating {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createRating($rs) : null;
    }

    // Utile per mostrare tutti i voti di un libro specifico
    public function getRatingsByBook(int $bookId): array {
        $this->stmtGetByBook->bindValue(1, $bookId, PDO::PARAM_INT);
        $this->stmtGetByBook->execute();
        $result = [];
        while ($rs = $this->stmtGetByBook->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createRating($rs);
        }
        return $result;
    }

    public function storeRating(Rating $rating): ?Rating {
        if ($rating->getId() !== null) {
         $this->stmtUpdate->bindValue(1, $rating->getUser()->getId(), PDO::PARAM_INT);
         $this->stmtUpdate->bindValue(2, $rating->getBook()->getId(), PDO::PARAM_INT);
         $this->stmtUpdate->bindValue(3, $rating->getValue(), PDO::PARAM_INT);
         $this->stmtUpdate->bindValue(4, $rating->getDate(), PDO::PARAM_STR);
         $this->stmtUpdate->bindValue(5, $rating->getId(), PDO::PARAM_INT);
         
         if ($this->stmtUpdate->execute()) return $rating;
        } else {
            $this->stmtInsert->bindValue(1, $rating->getUser()->getId(), PDO::PARAM_INT);
            $this->stmtInsert->bindValue(2, $rating->getBook()->getId(), PDO::PARAM_INT);
            $this->stmtInsert->bindValue(3, $rating->getValue(), PDO::PARAM_INT);
            $this->stmtInsert->bindValue(4, $rating->getDate(), PDO::PARAM_STR);
            
            if ($this->stmtInsert->execute()) {
                $rating->setId((int)$this->conn->lastInsertId());
                return $rating;
            }
        }
        return null;
    
    }

    public function deleteRating(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    }

    private function createRating(array $rs): Rating {
        // Usiamo il Proxy per gestire le relazioni con User e Book (Lazy Loading)
        $rating = new RatingProxy($this->dataLayer);
        $rating->setId((int)$rs['ID']);
        $rating->setValue((int)$rs['VALORE']);
        $rating->setDate($rs['DATA']);
        
        // Passiamo gli ID al Proxy
        $rating->setUserId((int)$rs['ID_UTENTE']);
        $rating->setBookId((int)$rs['ID_LIBRO']);
        
        return $rating;
    }
}