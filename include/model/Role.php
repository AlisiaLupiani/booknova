<?php

class Role {

    private ?int $id;
    private ?string $role;

    public function __construct() {
        $this->id = null;
        $this->role = null;
    }

    // Getter
    public function getId(): ?int { return $this->id; }
    public function getRuolo(): ?string { return $this->role; }

    // Setter
    public function setId(?int $id): void { $this->id = $id; }
    public function setRuolo(?string $role): void { $this->role = $role; }

    // Metodo toString aggiunto
    public function toString(): string {
        return $this->role ?? '';
    
        
    }
}