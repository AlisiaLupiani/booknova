<?php

require_once(__DIR__.'/Book.php');
require_once(__DIR__.'/Offer.php');
require_once(__DIR__.'/Book.php');
require_once(__DIR__.'/Offer.php');
require_once(__DIR__.'/User.php');

class BookOffer {
    private ?int $id;
    private ?Book $book;
    private ?Offer $offer;
     private ?User $user;

    public function __construct() {
        $this->id = null;
        $this->book = null;
        $this->offer = null;
            $this->user = null;
    }

    public function getId(): ?int { return $this->id; }
    public function getBook(): ?Book{ return $this->book; }
    public function getOffer(): ?Offer { return $this->offer; }
    public function getUser(): ?User { return $this->user; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setBook(?Book $book): void { $this->book = $book; }
    public function setOffer(?Offer $offer): void { $this->offer = $offer; }
    public function setUser(?User $user): void { $this->user = $user; }
     public function toString(): string {
        return "ID: " . $this->id . " - Book: " . $this->book . " - Offer: " . $this->offer;
    }
}


  