<?php

require_once("include/model/Cart.php");

class CartProxy extends Cart{

    private ?DataLayer $dataLayer;

    private ?int $userId;

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

    public function getUserId(): ?int { return $this->userId; }
    public function setUserId(?int $userId): void {$this->userId = $userId;}



    public function getUser(): ?User{
        if(parent:: getUser() == null && $this->userId > 0 ){
            parent:: setUser((($this -> dataLayer)->getUserDao())->getUserById($this->userId));
        }
        return parent::getUser();
    }

    public function getItems(): array {
        if (empty(parent::getItems())) {
            parent::setItems(
                ($this->dataLayer)->getCartDao()->getCartItemsByUserId($this->userId)
            );
        }
        return parent::getItems();
    }
}