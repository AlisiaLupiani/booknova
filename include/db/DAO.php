<?php


class DAO{

    protected ?DataLayer $dataLayer;
    protected ?PDO $conn;

    public function __construct(DataLayer $dataLayer){
        $this->dataLayer = $dataLayer;
        $this->conn = $dataLayer->getConnection();
    }
}
