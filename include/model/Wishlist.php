<?php

require_once('./User.php');
require_once('./Book.php');

class Wishlist {
    private ?int $id;
    private ?string $createdAt;
    private ?User $user; // Oggetto User, non int
    private ?Book $book; // Oggetto Book, non int

    public function __construct() {
        $this->id = null;
        $this->createdAt = date("Y-m-d H:i:s"); // Inizializziamo con la data attuale
        $this->user = null;
        $this->book = null;
    }

    public function getId(): ?int { return $this->id; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getUser(): ?User { return $this->user; }
    public function getBook(): ?Book { return $this->book; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setCreatedAt(?string $createdAt): void { $this->createdAt = $createdAt; }
    public function setUser(?User $user): void { $this->user = $user; }
    public function setBook(?Book $book): void { $this->book = $book; }

    public function toString(): ?String {
        return "Wishlist ID: " . $this->id . " - Created At: " . $this->createdAt;
    
        
    }
}