<?php

require_once('include/model/Category.php');

class CategoryProxy extends Category{

    private ?DataLayer $dataLayer;

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }


}