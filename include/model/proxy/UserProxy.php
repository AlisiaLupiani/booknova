<?php

require_once("include/model/User.php");


class UserProxy extends User{

    private ?DataLayer $dataLayer;


    private int $roleId

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

    public function getRoleId(): int {return $this->roleId}

    public function setRoleId(int $roleId): void {$this->roleId = $roleId}



    public function getRole(): ?Role{
        if(parent::getRole() == null && $this->roleId > 0){
            parent::setRole((($this->dataLayer)->getRoleDAO())->getRoleById($this->roleId));
        }
        return parent::getRole();
    }

}
