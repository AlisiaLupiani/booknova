<?php

require_once(__DIR__.'/User.php');
require_once(__DIR__.'/Book.php');

class Cart {
    private ?int $id;
    private ?User $user;
    private ?array $books;
    private ?int $quantity;

    public function __construct() {
        $this->id = null;
        $this->user = null;
        $this->books = null;
        $this->quantity = 1;
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function getBooks(): ?array { return $this->books; }
    public function getQuantity(): ?int { return $this->quantity; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUser(?User $user): void { $this->user = $user; }
    public function setBooks(?array $books): void { $this->books = $books; }
    public function setQuantity(?int $quantity): void { $this->quantity = $quantity; }

   
}