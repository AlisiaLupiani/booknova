<?php

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
    
    public function getUserId(): ?int { return $this->userId; }
    public function setUserId(?int $userId): void {$this->userId = $userId;}

    public function getBookId(): ?int {return $this->bookId;}
    public function setBookId(?int $bookId): void {$this->bookId = $bookId;}

}