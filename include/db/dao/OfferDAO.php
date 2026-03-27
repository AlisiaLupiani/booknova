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

        $this->stmtInsertOffer = $this->conn->prepare("INSERT INTO OFFERTA (PERCENTUALE) VALUES (?);");

        $this->stmtUpdateOffer = $this->conn->prepare("UPDATE OFFERTA SET PERCENTUALE = ? WHERE ID = ?;");
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
            $this->stmtUpdateOffer->bindValue(1, $offer->getPercentage(), PDO::PARAM_INT);
            $this->stmtUpdateOffer->bindValue(2, $offer->getId(), PDO::PARAM_INT);
            if ($this->stmtUpdateOffer->execute()) {
                return $offer;
            }           
        }
    }

    public function createOffer(array $rs): Offer {
        $offer = new OfferProxy($this->dataLayer);  
        $offer->setId((int)$rs['ID']);
        $offer->setPercentage((int)$rs['PERCENTUALE']);
        return $offer;
        
    }

    public function deleteOfferById(int $id): bool {
        $this->stmtDeleteOffer->bindValue(1, $id, PDO::PARAM_INT);
        return $this->stmtDeleteOffer->execute();
    }

    
}


