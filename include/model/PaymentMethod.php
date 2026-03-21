<?php

class PaymentMethod {
    private ?int $id;
    private ?string $name;

    public function __construct() {
        $this->id = null;
        $this->name = '';
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setName(?string $name): void { $this->name = $name; }

    // Metodo per ottenere la stringa descrittiva
    public function toString(): string {
        return $this->name ?? '';
    }
}