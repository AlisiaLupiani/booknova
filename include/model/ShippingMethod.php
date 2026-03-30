<?php

class ShippingMethod {
    private ?int $id;
    private ?string $name;
    private ?float $cost;

    public function __construct() {
        $this->id = null;
        $this->name = '';
        $this->cost = 0.0;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getCost(): ?float { return $this->cost; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setName(?string $name): void { $this->name = $name; }
    public function setCost(?float $cost): void { $this->cost = $cost; }

    // Metodo per visualizzare il metodo di spedizione (es. Standard - 5.00€)
    public function toString(): string {
        return $this->name . " - " . $this->cost . "€". " _ ". $this->id;
    }
}
    
    
