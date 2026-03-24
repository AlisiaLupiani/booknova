<?php

require_once("include/model/BookOffer.php");
require_once("include/model/proxy/BookOfferProxy.php");
require_once("include/db/DAO.php");

class BookOfferDAO extends DAO {

    private PDOStatement $stmtGetById;
    private PDOStatement $stmtGetByBook;
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
        // Usiamo gli ID degli oggetti collegati
        $idLibro = $bo->getBook() ? $bo->getBook()->getId() : null;
        $idOfferta = $bo->getOffer() ? $bo->getOffer()->getId() : null;

        $this->stmtInsert->bindValue(1, $idLibro, PDO::PARAM_INT);
        $this->stmtInsert->bindValue(2, $idOfferta, PDO::PARAM_INT);

        if ($this->stmtInsert->execute()) {
            $bo->setId((int)$this->conn->lastInsertId());
            return $bo;
        }
        return null;
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