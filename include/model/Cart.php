<?php

require_once('./User.php');
require_once('./Book.php');

class Cart {
    private ?int $id;
    private ?User $user;
    private ?Book $book;
    private ?int $quantity;

    public function __construct() {
        $this->id = null;
        $this->user = null;
        $this->book = null;
        $this->quantity = 1;
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function getBook(): ?Book { return $this->book; }
    public function getQuantity(): ?int { return $this->quantity; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUser(?User $user): void { $this->user = $user; }
    public function setBook(?Book $book): void { $this->book = $book; }
    public function setQuantity(?int $quantity): void { $this->quantity = $quantity; }

    public function toString(): string {
        $userName = $this->user ? $this->user->getFirstName() : "N/A";
        $bookTitle = $this->book ? $this->book->getTitle() : "N/A";
        return "Cart ID: " . $this->id . " | User: " . $userName . " | Book: " . $bookTitle . " | Qty: " . $this->quantity;
    }
}