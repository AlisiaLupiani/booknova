<?php

require_once('include/model/BookOffer.php');
require_once('include/model/User.php');
require_once('include/model/Book.php');


class BookOfferProxy extends BookOffer{

    private ?DataLayer $dataLayer;

    private ?int $userId;
    private ?int $bookId;

     public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }
    
    public function getUserId(): ?int { return $this->userId;}
    public function setUserId(?int $userId): void {$this->userId = $userId;}
    public function getBookId(): ?int { return $this->bookId; }
    public function setBookId(?int $bookId): void {$this->bookId = $bookId;}

    
    
    public function getUser(): ?User{
        if(parent::getUser() == null && $this->userId > 0 ){
            parent::setUser((($this -> dataLayer)->getUserDao())->getUserById($this->userId));
        }
        return parent::getUser();
    }

    public function getBook(): ?Book{
        if(parent::getBook() == null && $this->bookId > 0 ){
            parent::setBook((($this -> dataLayer)->getBookDao())->getBookById($this->bookId));
        }
        return parent::getBook();
    }
}