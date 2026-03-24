<?php

include_once('include/model/Format.php');

class CategoryProxy extends Format{

    private ?DataLayer $dataLayer;

    public function_construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }
}