<?php

class Condition {
    private ?int $id;
    private ?string $condition;

    public function __construct() {
        $this->id = null;
        $this->condition = '';
    }

    // Getter
    public function getId(): ?int { return $this->id; }
    public function getCondition(): ?string { return $this->condition; } // Corretto: prima c'era un loop ricorsivo

    // Setter
    public function setId(?int $id): void { $this->id = $id; }
    public function setCondition(?string $condition): void { $this->condition = $condition; } // Corretto: prima usava $this->name

    // Other function
    public function toString(): string {
        return $this->condition ?? '';
    }
}