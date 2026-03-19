<?php

class Ruolo{

    private ?int $id;
    private ?string $role;


    public function public function __construct() {
        $this->id = null;
        $this->role = null;
    }

//getter
    public function getId(): ?int {return $this->id;}
    public function getRuolo(): ?string {return $this->role;}

//setter
    public function setId(?int $id): void {$this->id = $id;}
    public function setRuolo(?string $role): void { $this->role = $role;}
}


