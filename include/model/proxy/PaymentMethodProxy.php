<?php

require_once("include/model/PaymentMethod.php");

class PaymentMethodProxy extends PaymentMethod{

    private ?DataLayer $dataLayer;

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }


}