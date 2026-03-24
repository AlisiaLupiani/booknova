<?php

include_once("include/model/PaymentMethod.php");

class PaymentMethodProxy extends PaymentMethod{

    private ?DataLayer $dataLayer;

    public function_construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }


}