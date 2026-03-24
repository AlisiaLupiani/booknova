<?php

include_once('include/model/Offer.php');

class CategoryProxy extends Offer{

    private ?DataLayer $dataLayer;

    public function _construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;

    }
}