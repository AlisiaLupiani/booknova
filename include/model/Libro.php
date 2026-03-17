<?php

class Libro {
    private ?int $id;
    private ?string $title;
    private ?float $price;
    private ?string $description;
    private ?int $authorId;
    private ?int $publisherId;
    private ?int $categoryId;
    private ?int $formatId;
    private ?int $conditionId;

    public function __construct() {
        $this->id = null;
        $this->title = '';
        $this->price = null;
        $this->description = '';
        $this->authorId = null;
        $this->publisherId = null;
        $this->categoryId = null;
        $this->formatId = null;
        $this->conditionId = null;
    }

    // Getter
    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function getPrice(): ?float { return $this->price; }
    public function getDescription(): ?string { return $this->description; }
    public function getAuthorId(): ?int { return $this->authorId; }
    public function getPublisherId(): ?int { return $this->publisherId; }
    public function getCategoryId(): ?int { return $this->categoryId; }
    public function getFormatId(): ?int { return $this->formatId; }
    public function getConditionId(): ?int { return $this->conditionId; }

    // Setter
    public function setId(?int $id) { $this->id = $id; }
    public function setTitle(?string $title) { $this->title = $title; }
    public function setPrice(?float $price) { $this->price = $price; }
    public function setDescription(?string $description) { $this->description = $description; }
    public function setAuthorId(?int $authorId) { $this->authorId = $authorId; }
    public function setPublisherId(?int $publisherId) { $this->publisherId = $publisherId; }
    public function setCategoryId(?int $categoryId) { $this->categoryId = $categoryId; }
    public function setFormatId(?int $formatId) { $this->formatId = $formatId; }
    public function setConditionId(?int $conditionId) { $this->conditionId = $conditionId; }

    // Other function
    public function toString(): ?string {
        return "Libro: " . $this->title . " (ID: " . $this->id . ") - Prezzo: " . $this->price;
    }
}