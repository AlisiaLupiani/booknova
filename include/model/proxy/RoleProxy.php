<?php

require_once("include/model/Role.php");

class RoleProxy extends Role {

    private ?DataLayer $dataLayer;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

}