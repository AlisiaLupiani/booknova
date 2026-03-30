<?php

require_once("include/model/BookOffer.php");
require_once("include/model/proxy/BookOfferProxy.php");
require_once("include/db/DAO.php");

class BookOfferDAO extends DAO {

    private PDOStatement $stmtGetById;
    private PDOStatement $stmtGetByBook;
    private PDOStatement $stmtBookOffer;
    private PDOStatement $stmtUpdate;
    private PDOStatement $stmtInsert;
    private PDOStatement $stmtDelete;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        $this->stmtGetById = $this->conn->prepare("SELECT * FROM LIBRO_OFFERTA WHERE ID = ?;");
        $this->stmtGetByBook = $this->conn->prepare("SELECT * FROM LIBRO_OFFERTA WHERE ID_LIBRO = ?;");
        $this->stmtInsert = $this->conn->prepare("INSERT INTO LIBRO_OFFERTA (ID_LIBRO, ID_OFFERTA) VALUES (?, ?);");
        $this->stmtDelete = $this->conn->prepare("DELETE FROM LIBRO_OFFERTA WHERE ID = ?;");
    }

    public function getBookOfferById(int $id): ?BookOffer {
        $this->stmtGetById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetById->execute();
        $rs = $this->stmtGetById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createBookOffer($rs) : null;
    }

    public function storeBookOffer(BookOffer $bo): ?BookOffer {
        if($bo->getId() !== null) {
            $this->stmtUpdate->bindValue(1, $bo->getBook()->getId(), PDO::PARAM_INT);
            $this->stmtUpdate->bindValue(2, $bo->getOffer()->getId(), PDO::PARAM_INT);
            $this->stmtUpdate->bindValue(3, $bo->getUser()->getId(), PDO::PARAM_INT);
            $this->stmtUpdate->bindValue(3, $bo->getId(), PDO::PARAM_INT);
            

            if($this->stmtUpdate->execute()) return $bo;
        }else{
            $this->stmtInsert->bindValue(1, $bo->getBook()->getId(), PDO::PARAM_INT);
            $this->stmtInsert->bindValue(2, $bo->getOffer()->getId(), PDO::PARAM_INT);
            $this->stmtInsert->bindValue(3, $bo->getUser()->getId(), PDO::PARAM_INT);
            

            if($this->stmtInsert->execute()){
                $bo->setId((int)$this->conn->lastInsertId());
                return $bo;
            }

        }

        return null;     

    }

    public function deleteBookOffer(int $id): bool {
        $this->stmtDelete->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDelete->execute();
    
            
    }

    private function createBookOffer(array $rs): BookOffer {
        // Usiamo il Proxy perché BookOffer deve caricare Book e Offer in modo "Lazy"
        $bo = new BookOfferProxy($this->dataLayer);
        $bo->setId((int)$rs['ID']);
        
        // Passiamo gli ID al Proxy
        $bo->setBookId((int)$rs['ID_LIBRO']);
        $bo->setOfferId((int)$rs['ID_OFFERTA']);
        
        return $bo;
    }
}