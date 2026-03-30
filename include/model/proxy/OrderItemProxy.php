<?php

include_once('include/model/OrderItem.php');

class OrderItemProxy extends OrderItem{

    private ?DataLayer $dataLayer;

    private ?int $orderId;
    private ?int $bookId;

    public function __construct(?DataLayer $dataLayer){

        parent:: __construct();
        $this->dataLayer = $dataLayer;

    }
    
    public function getUserId(): ?int {return $this->orderId;}
    public function setUserId(?int $userId): ?int {return $this->orderId = $userId;}

    public function getBookId(): ?int{return $this->bookId;}
    public function setBookId(?int $bookId): ?int{return $this->bookId = $bookId;}

    
    
    public function getOrder(): ?Order{
        if(parent:: getOrder() == null && $this->orderId > 0 ){
            parent:: setOrder((($this -> dataLayer)->getUserDao())->getUserById($this->orderId));
        }
        return parent::getOrder();
    }

    public function getBook(): ?Book{
        if(parent:: getBook() == null && $this->bookId > 0 ){
            parent:: setBook((($this -> dataLayer)->getBookDao())->getBookById($this->bookId));
        }
        return parent::getBook();
    }
    



}