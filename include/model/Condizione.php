<?php

class Condizione{
    private ?int $id;
    private ?String $condition;

    public function __construct() {
        $this->id = null;
        $this->condition = '';
    }

    // Getter
    public function getId(): ?int {return $this->id;}
    public function getCondition(): ?string {return $this->getCondition;}

    // Setter
    public function setId(?int $id) { $this->id = $id; }
    public function setCondition(?string $condition) { $this->name = $condition; }
}