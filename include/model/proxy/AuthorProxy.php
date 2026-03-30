<?php

require_once('include/model/Author.php');

class AuthorProxy extends Author{

    private ?DataLayer $dataLayer;

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }


}