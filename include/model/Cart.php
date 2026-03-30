<?php

require_once(__DIR__.'/User.php');
require_once(__DIR__.'/Book.php');

class Cart {
    private ?User $user;
    private array $items;

    public function __construct() {
        $this->user = null;
        $this->items = null;
    }

    public function getUser(): ?User { return $this->user; }
    public function getItems(): array { return $this->items; }

    public function setUser(?User $user): void { $this->user = $user; }
    public function setItems(array $items): void { $this->items = $items; }
}