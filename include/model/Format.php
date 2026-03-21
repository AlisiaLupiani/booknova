<?php

class Format {
    private ?int $id;
    private ?string $format;

    public function __construct() {
        $this->id = null;
        $this->format = '';
    }

    // Getter
    public function getId(): ?int { return $this->id; }
    public function getFormat(): ?string { return $this->format; } // Corretto: variabile, non funzione

    // Setter
    public function setId(?int $id): void { $this->id = $id; }
    public function setFormat(?string $format): void { $this->format = $format; } // Corretto: variabile $format

    // Other function
    public function toString(): string {
        return $this->format ?? '';
    }
}