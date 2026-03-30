<?php

require_once(__DIR__.'/User.php');
require_once(__DIR__.'/Book.php');

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
        return "Carrello ID: " . $this->id . "\n" .
               "Utente: " . $this->user->toString() . "\n" .
               "Libro: " . $this->book->toString() . "\n" .
               "Quantità: " . $this->quantity;
    
        
    }
}