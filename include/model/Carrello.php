<?php

class Cart {
    private ?int $id;
    private ?int $userId;
    private ?int $bookId;
    private ?int $quantity;

    public function __construct() {
        $this->id = null;
        $this->userId = null;
        $this->bookId = null;
        $this->quantity = 1;
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }
    public function getBookId(): ?int { return $this->bookId; }
    public function getQuantity(): ?int { return $this->quantity; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUserId(?int $userId): void { $this->userId = $userId; }
    public function setBookId(?int $bookId): void { $this->bookId = $bookId; }
    public function setQuantity(?int $quantity): void { $this->quantity = $quantity; }
}