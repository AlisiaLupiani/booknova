<?php

include_once('include/model/Format.php');

class FormatProxy extends Format{

    private ?DataLayer $dataLayer;

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }
}