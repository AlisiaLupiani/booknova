<?php

require_once(__DIR__.'/Order.php');
require_once(__DIR__.'/Book.php');

class OrderItem {
    private ?int $id;
    private ?Order $order; // Oggetto Order invece di int
    private ?Book $book;   // Oggetto Book invece di int
    private ?int $quantity;
    private ?float $unitPrice;

    public function __construct() {
        $this->id = null;
        $this->order = null;
        $this->book = null;
        $this->quantity = 0;
        $this->unitPrice = 0.0;
    }

    public function getId(): ?int { return $this->id; }
    public function getOrder(): ?Order { return $this->order; }
    public function getBook(): ?Book { return $this->book; }
    public function getQuantity(): ?int { return $this->quantity; }
    public function getUnitPrice(): ?float { return $this->unitPrice; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setOrder(?Order $order): void { $this->order = $order; }
    public function setBook(?Book $book): void { $this->book = $book; }
    public function setQuantity(?int $quantity): void { $this->quantity = $quantity; }
    public function setUnitPrice(?float $unitPrice): void { $this->unitPrice = $unitPrice; }

    public function toString(): string {
        $bookTitle = $this->book ? $this->book->getTitle() : "N/A";
        return "Dettaglio #{$this->id} | Libro: {$bookTitle} | Qta: {$this->quantity} | Prezzo: {$this->unitPrice}€";
    }
}