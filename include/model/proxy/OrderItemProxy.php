<?php

include_once('include/model/OrderItem.php');

class OrderItemProxy extends OrderItem{

    private ?DataLayer $dataLayer;

    private ?int $userId;
    private ?int $bookId;

    public function __construct(?DataLayer $dataLayer){

        parent:: __construct();
        $this->dataLayer = $dataLayer;

    }
    
    private function getUserId(): ?int {return $this->userId};
    private function setUserId(?int $userId): ?int {return $this->userId = $userId};

    private function getBookId(): ?int{return $this->bookId};
    private function setBookId(?int $bookId): ?int{return $this->bookId = $bookId};

    
    
    public function getUser(): ?User{
        if(parent:: getUser() == null && $this->userId > 0 ){
            parent:: setUser((($this -> dataLayer)->getUserDao())->getUserById($this->userId));
        }
        return parent::getUser();
    }

    public function getBook(): ?Book{
        if(parent:: getBook() == null && $this->bookId > 0 ){
            parent:: setBook((($this -> dataLayer)->getBookDao())->getBookById($this->bookId));
        }
        return parent::getBook();
    }
    



}