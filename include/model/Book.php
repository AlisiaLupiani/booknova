<?php


require_once('./Author.php')
require_once('./Publisher.php')
require_once('./Category.php')
require_once('./Format.php')
require_once('./Condition.php')

class Libro {
    private ?int $id;
    private ?string $title;
    private ?float $price;
    private ?string $description;
    private ?Author $author;
    private ?Editor $publisher;
    private ?Category $category;
    private ?Format $format;
    private ?Condition $condition;

    public function __construct() {
        $this->id = null;
        $this->title = '';
        $this->price = null;
        $this->description = '';
        $this->author = null;
        $this->publisher = null;
        $this->category = null;
        $this->format = null;
        $this->condition = null;
    }

    // Getter
    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function getPrice(): ?float { return $this->price; }
    public function getDescription(): ?string { return $this->description; }
    public function getAuthor(): ?Authir { return $this->author; }
    public function getPublisher(): ?Publisher { return $this->publisher; }
    public function getCategory(): ?Category { return $this->category; }
    public function getFormat(): ?Format { return $this->format; }
    public function getCondition(): ?Condition{ return $this->condition; }

    // Setter
    public function setId(?int $id): void { $this->id = $id; }
    public function setTitle(?string $title): void { $this->title = $title; }
    public function setPrice(?float $price): void { $this->price = $price; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setAuthor(?Author $author): void { $this->author = $author; }
    public function setPublisher(?Publisher $publisher): void { $this->publisher = $publisher; }
    public function setCategory(?Category $category): void { $this->category = $category; }
    public function setFormat(?Format $format): void { $this->format = $format; }
    public function setCondition(?Condition $condition): void { $this->condition = $condition; }

    // Other function
    public function toString(): ?string {
        return $this->title;
    }
}