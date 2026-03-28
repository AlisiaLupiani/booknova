<?php

include_once("include/model/User.php");

class PublisherProxy extends Publisher{
    
    private ?DataLayer $dataLayer;

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }
    
}