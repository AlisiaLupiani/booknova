<?php

require_once("include/model/CartItem.php");

class CartItemProxy extends CartItem{

    private ?DataLayer $dataLayer;

    private ?int $userId;
    private ?int $bookId;

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

    public function getUserId(): ?int { return $this->userId; }
    public function setUserId(?int $userId): void {$this->userId = $userId;}

    public function getBookId(): ?int { return $this->bookId; }
    public function setBookId(?int $bookId): void {$this->bookId = $bookId;}



    public function getUser(): ?User{
        if(parent:: getUser() == null && $this->userId > 0 ){
            parent:: setUser((($this -> dataLayer)->getUserDao())->getUserById($this->userId));
        }
        return parent::getUser();
    }

     public function getItems(): array {
        if (parent::getItems() == null) {
            parent::setItems(($this->dataLayer)->getCartDao()->getCartItemByUserId($this->userId));
        }
        return parent::getItems();
    }
}