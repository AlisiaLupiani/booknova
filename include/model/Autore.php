<?php

class Autore {
    private ?int $id;
    private ?string $name;
    private ?string $biography;

    public function __construct(?int $id = null, ?string $name = null, ?string $biography = null) {
        $this->id = $id;
        $this->name = '';
        $this->biography = '';
    }

    // Getter
    public function getId(): ?int {return $this->id;}
    public function getName(): ?string {return $this->name;}
    public function getBiography(): ?string {return $this->biography;}

    // Setter
    public function setId(?int $id) { $this->id = $id; }
    public function setName(?string $name) { $this->name = $name; }
    public function setBiography(?string $biography) { $this->biography = $biography; }
}