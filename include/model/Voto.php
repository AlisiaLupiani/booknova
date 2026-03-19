<?php

class Rating {
    private ?int $id;
    private ?int $userId;
    private ?int $bookId;
    private ?int $value;
    private ?string $date;

    public function __construct() {
        $this->id = null;
        $this->userId = null;
        $this->bookId = null;
        $this->value = 0;
        $this->date = '';
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }
    public function getBookId(): ?int { return $this->bookId; }
    public function getValue(): ?int { return $this->value; }
    public function getDate(): ?string { return $this->date; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUserId(?int $userId): void { $this->userId = $userId; }
    public function setBookId(?int $bookId): void { $this->bookId = $bookId; }
    public function setValue(?int $value): void { $this->value = $value; }
    public function setDate(?string $date): void { $this->date = $date; }
}