<?php


require_once('./Autore.php')
require_once('./Editore.php')
require_once('./Categoria.php')
require_once('./Formato.php')
require_once('./Condizione.php')

class Libro {
    private ?int $id;
    private ?string $title;
    private ?float $price;
    private ?string $description;
    private ?Autore $author;
    private ?Editore $publisher;
    private ?Categoria $category;
    private ?Formato $format;
    private ?Condizione $condition;

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
    public function getAuthor(): ?Autore { return $this->author; }
    public function getPublisher(): ?Editore { return $this->publisher; }
    public function getCategory(): ?Categoria { return $this->category; }
    public function getFormat(): ?Formato { return $this->format; }
    public function getCondition(): ?Condizione { return $this->condition; }

    // Setter
    public function setId(?int $id) { $this->id = $id; }
    public function setTitle(?string $title) { $this->title = $title; }
    public function setPrice(?float $price) { $this->price = $price; }
    public function setDescription(?string $description) { $this->description = $description; }
    public function setAuthor(?Autore $author) { $this->author = $author; }
    public function setPublisher(?Editore $publisher) { $this->publisher = $publisher; }
    public function setCategory(?Categoria $category) { $this->category = $category; }
    public function setFormat(?Formato $format) { $this->format = $format; }
    public function setCondition(?Condizione $condition) { $this->condition = $condition; }

    // Other function
    public function toString(): ?string {
        return $this->title;
    }
}