<?php

require_once("include/model/CartItem.php");

class CartItemProxy extends CartItem {

    private ?DataLayer $dataLayer;
    private ?int $userId;
    private ?int $bookId;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct();
        $this->dataLayer = $dataLayer;
        $this->userId = null;
        $this->bookId = null;
    }

    public function getUserId(): ?int { return $this->userId; }
    public function setUserId(?int $userId): void { $this->userId = $userId; }

    public function getBookId(): ?int { return $this->bookId; }
    public function setBookId(?int $bookId): void { $this->bookId = $bookId; }

    // Carica l'utente dal DB solo se serve
    public function getUser(): ?User {
        if (parent::getUser() == null && $this->userId > 0) {
            parent::setUser(($this->dataLayer->getUserDAO())->getUserById($this->userId));
        }
        return parent::getUser();
    }

    // Carica il libro dal DB solo se serve (Questo mancava ed è fondamentale per cart.php!)
    public function getBook(): ?Book {
        if (parent::getBook() == null && $this->bookId > 0) {
            parent::setBook(($this->dataLayer->getBookDAO())->getBookById($this->bookId));
        }
        return parent::getBook();
    }
}