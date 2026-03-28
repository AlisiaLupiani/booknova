<?php

class Publisher {
    private ?int $id;
    private ?string $name;

    public function __construct() {
        $this->id = null;
        $this->name = '';
    }

    // Getter
    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }

    // Setter
    public function setId(?int $id): void { $this->id = $id; }
    public function setName(?string $name): void { $this->name = $name; }

    // Metodo toString
    public function toString(): string {
        return $this->name ?? '';
    }
}