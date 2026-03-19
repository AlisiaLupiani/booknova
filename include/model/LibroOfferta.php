<?php

class BookOffer {
    private ?int $id;
    private ?int $bookId;
    private ?int $offerId;

    public function __construct() {
        $this->id = null;
        $this->bookId = null;
        $this->offerId = null;
    }

    public function getId(): ?int { return $this->id; }
    public function getBookId(): ?int { return $this->bookId; }
    public function getOfferId(): ?int { return $this->offerId; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setBookId(?int $bookId): void { $this->bookId = $bookId; }
    public function setOfferId(?int $offerId): void { $this->offerId = $offerId; }

    public function toString(): string {
        return "ID: " . $this->id . " - Book: " . $this->bookId . " - Offer: " . $this->offerId;
    }
}