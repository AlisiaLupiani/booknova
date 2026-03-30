<?php

require_once('include/model/Offer.php');

class OfferProxy extends Offer{

    private ?DataLayer $dataLayer;

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;

    }
}