<?php

class Review {
    private ?int $id;
    private ?int $userId;
    private ?int $bookId;
    private ?string $content;
    private ?string $date;

    public function __construct() {
        $this->id = null;
        $this->userId = null;
        $this->bookId = null;
        $this->content = '';
        $this->date = '';
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }
    public function getBookId(): ?int { return $this->bookId; }
    public function getContent(): ?string { return $this->content; }
    public function getDate(): ?string { return $this->date; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUserId(?int $userId): void { $this->userId = $userId; }
    public function setBookId(?int $bookId): void { $this->bookId = $bookId; }
    public function setContent(?string $content): void { $this->content = $content; }
    public function setDate(?string $date): void { $this->date = $date; }
}