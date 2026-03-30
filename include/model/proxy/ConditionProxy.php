<?php

require_once('include/model/Condition.php');

class ConditionProxy extends Condition{

    private ?DataLayer $dataLayer;

    public function  __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;

    }
}