<?php

require_once('include/model/Review.php');

class ReviewProxy extends Review{

    private ?DataLayer $dataLayer;

    private ?int $userId;
    private ?int $bookId;

    public function _construct(?DataLayer dataLayer){

        parent :: -construct();
        $this->dataLayer = $dataLayer;

    }
    private function getUserId(): ?int {return $this->userId};
    private function setUserId(?int $userId): ?int {return $this->userId = $userId};

    private function getBookId(): ?int{return $this->bookId};
    private function setBookId(?int $bookId): ?int{return $this->bookId = $bookId};


    


}