<?php

class Offerta {
    private ?int $id;
    private ?float $value;
    private ?string $startDate;
    private ?string $endDate;

    public function __construct() {
        $this->id = null;
        $this->value = null; 
        $this->startDate = '';
        $this->endDate = '';
    }

    // Getter
    public function getId(): ?int { return $this->id; }
    public function getValue(): ?float { return $this->value; }
    public function getStartDate(): ?string { return $this->startDate; }
    public function getEndDate(): ?string { return $this->endDate; }

    // Setter
    public function setId(?int $id) { $this->id = $id; }
    public function setValue(?float $value) { $this->value = $value; }
    public function setStartDate(?string $startDate) { $this->startDate = $startDate; }
    public function setEndDate(?string $endDate) { $this->endDate = $endDate; }

    // Other function
    public function toString(): ?string {
        return "Offerta ID: " . $this->id . " - Value: " . $this->value;
    }
}