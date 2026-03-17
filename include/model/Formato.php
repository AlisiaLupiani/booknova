<?php

class Formato{
    private ?int $id;
    private ?string $format;

    public function __construct() {
        $this->id = null;
        $this->format = '';

    }
    // Getter
    public function getId(): ?int {return $this->id;}
    public function getFormat(): ?string {return $this->getFormat;}

    // Setter
    public function setId(?int $id) { $this->id = $id; }
    public function setFormat(?string $format) { $this->name = $format; }
}