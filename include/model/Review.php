<?php

require_once('./User.ph');
require_once('./Book.ph');


class Review {
    private ?int $id;
    private ?User $user;
    private ?Book $book;
    private ?string $content;
    private ?string $date;

    public function __construct() {
        $this->id = null;
        $this->user = null;
        $this->book = null;
        $this->content = '';
        $this->date = '';
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function getBook(): ?Book { return $this->book; }
    public function getContent(): ?string { return $this->content; }
    public function getDate(): ?string { return $this->date; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUser(?User $user): void { $this->user = $user; }
    public function setBook(?Book $book): void { $this->book = $book; }
    public function setContent(?string $content): void { $this->content = $content; }
    public function setDate(?string $date): void { $this->date = $date; }
}