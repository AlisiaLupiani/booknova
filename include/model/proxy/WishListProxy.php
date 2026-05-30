<?php

require_once('include/model/Wishlist.php');
require_once('include/db/dao/WishlistDAO.php');

class WishlistProxy extends Wishlist {

    private ?DataLayer $dataLayer;

    private ?int $userId = null;
    private ?int $bookId = null;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

    public function getUserId(): ?int { return $this->userId; }
    public function setUserId(?int $userId): void { $this->userId = $userId; }

    public function getBookId(): ?int { return $this->bookId; }
    public function setBookId(?int $bookId): void { $this->bookId = $bookId; }

    public function getUser(): ?User {
        if (parent::getUser() === null && $this->userId !== null) {
            parent::setUser(
                $this->dataLayer->getUserDAO()->getUserById($this->userId)
            );
        }
        return parent::getUser();
    }

    public function getBook(): ?Book {
        if (parent::getBook() === null && $this->bookId !== null) {
            parent::setBook(
                $this->dataLayer->getBookDAO()->getBookById($this->bookId)
            );
        }
        return parent::getBook();
    }
}

