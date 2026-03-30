<?php

include_once("include/model/Offer.php");
include_once("include/model/proxy/OfferProxy.php");
include_once("include/db/DAO.php");

class OfferDAO extends DAO {
    private PDOStatement $stmtGetOfferById;
    private PDOStatement $stmtGetAllOffers;
    private PDOStatement $stmtInsertOffer;
    private PDOStatement $stmtUpdateOffer;
    private PDOStatement $stmtDeleteOffer;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct($dataLayer);
        $this->init();
    }

    public function init(): void {
        $this->stmtGetOfferById = $this->conn->prepare("SELECT * FROM OFFERTA WHERE ID = ?;");
        $this->stmtGetAllOffers = $this->conn->prepare("SELECT * FROM OFFERTA;");

        $this->stmtInsertOffer = $this->conn->prepare("INSERT INTO OFFERTA (VALORE, DATA_INIZIO, DATA_FINE) VALUES (?, ?, ?);");
        $this->stmtUpdateOffer = $this->conn->prepare("UPDATE OFFERTA SET VALORE = ?, DATA_INIZIO = ?, DATA_FINE = ? WHERE ID = ?;");
        $this->stmtDeleteOffer = $this->conn->prepare("DELETE FROM OFFERTA WHERE ID = ?;");
    }

    public function getOfferById(int $id): ?Offer {

        $this->stmtGetOfferById->bindValue(1, $id, PDO::PARAM_INT);
        $this->stmtGetOfferById->execute();
        $rs = $this->stmtGetOfferById->fetch(PDO::FETCH_ASSOC);

        return $rs ? $this->createOffer($rs) : null;
    }

    public function getAllOffers(): array {
        $this->stmtGetAllOffers->execute();
        $result = [];
        while ($rs = $this->stmtGetAllOffers->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->createOffer($rs);
        }
        return $result;
    }

    public function storeOffer(Offer $offer): ?Offer {
        if ($offer->getId() !== null) {
            $this->stmtUpdateOffer->bindValue(1, $offer->getValue(), PDO::PARAM_FLOAT);
            $this->stmtUpdateOffer->bindValue(2, $offer->getStartDate(), PDO::PARAM_STR);
            $this->stmtUpdateOffer->bindValue(3, $offer->getEndDate(), PDO::PARAM_STR);
            $this->stmtUpdateOffer->bindValue(4, $offer->getId(), PDO::PARAM_INT);

            if($this->stmtUpdateOffer->execute()){
                return $offer;
            }
        } else {
            $this->stmtInsertOffer->bindValue(1, $offer->getValue(), PDO::PARAM_FLOAT);
            $this->stmtInsertOffer->bindValue(2, $offer->getStartDate(), PDO::PARAM_STR);
            $this->stmtInsertOffer->bindValue(3, $offer->getEndDate(), PDO::PARAM_STR);

            if($this->stmtInsertOffer->execute()){
                $offer->setId((int)$this->conn->lastInsertId());
                return $offer;
            }
            
        }
        return null;
            
    }


    public function createOffer(array $rs): Offer {
        $offer = new OfferProxy($this->dataLayer);  
        $offer->setId((int)$rs['ID']);
        $offer->setValue((float)$rs['VALORE']);
        $offer->setStartDate((string)$rs['DATA_INIZIO']);
        $offer->setEndDate((string)$rs['DATA_FINE']);

        return $offer;
        
    }

    public function deleteOfferById(int $id): bool {
        $this->stmtDeleteOffer->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDeleteOffer->execute();
    }

    
}


