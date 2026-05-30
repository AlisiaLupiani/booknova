<?php

require_once(__DIR__.'/User.php');
require_once(__DIR__.'/Book.php');

class Wishlist {
    protected ?int $id;
    protected ?string $createdAt;
    protected ?User $user;
    protected ?Book $book;

    public function __construct() {
        $this->id = null;
        $this->createdAt = date("Y-m-d H:i:s");
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
}
