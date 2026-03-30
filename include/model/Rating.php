<?php

class Rating {
    private ?int $id;
    private ?User $user;
    private ?Book $book;
    private ?int $value;
    private ?string $date;

    public function __construct() {
        $this->id = null;
        $this->user = null;
        $this->book = null;
        $this->value = 0;
        $this->date = '';
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function getBook(): ?Book { return $this->book; }
    public function getValue(): ?int { return $this->value; }
    public function getDate(): ?string { return $this->date; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUser(?User $user): void { $this->user = $user; }
    public function setBook(?Book $book): void { $this->book = $book; }
    public function setValue(?int $value): void { $this->value = $value; }
    public function setDate(?string $date): void { $this->date = $date; }

    public function toString(): ?String {
        return "Rating ID: " . $this->id . "\n" .
               "User: " . $this->user->toString() . "\n" .
               "Book: " . $this->book->toString() . "\n" .
               "Value: " . $this->value . "\n" .
               "Date: " . $this->date;
    
    }
}