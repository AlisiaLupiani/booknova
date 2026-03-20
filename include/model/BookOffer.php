<?php

require_once('./Book.ph');
require_once('./Offer.ph');

class BookOffer {
    private ?int $id;
    private ?Book $book;
    private ?Offer $offer;

    public function __construct() {
        $this->id = null;
        $this->book = null;
        $this->offer = null;
    }

    public function getId(): ?int { return $this->id; }
    public function getBook(): ?Book{ return $this->book; }
    public function getOffer(): ?Offer { return $this->offer; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setBook(?int $book): void { $this->book = $book; }
    public function setOffer(?int $offer): void { $this->offer = $offer; }

    public function toString(): string {
        return "ID: " . $this->id . " - Book: " . $this->book . " - Offer: " . $this->offer;
    }
}