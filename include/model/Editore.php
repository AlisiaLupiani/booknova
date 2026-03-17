<?php

class Editore{
    private ?int $id;
    private ?String $name;

    public function __construct() {
        $this->id = null;
        $this->name = '';
    }

    // Getter
    public function getId(): ?int {return $this->id;}
    public function getName(): ?string {return $this->name;}

    // Setter
    public function setId(?int $id) { $this->id = $id; }
    public function setName(?string $name) { $this->name = $name; }
}