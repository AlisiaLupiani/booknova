<?php

require_once(__DIR__.'/Role.php');


class User{
    private ?int $id;
    private ?string $name;
    private ?string $surname;
    private ?string $email;
    private ?string $password;
    private ?Role $role;



    public function __construct(){
        $this->id = null;
        $this->name = '';
        $this->surname = '';
        $this->email = '';
        $this->password = '';
        $this->role = null;
    }

    // Getter
    public function getId(): ?int {return $this->id;}
    public function getName(): ?string {return $this->name;}
    public function getSurname(): ?string {return $this->surname;}
    public function getEmail(): ?string {return $this->email;}
    public function getPassword(): ?string {return $this->password;}
    public function getRole(): ?Role {return $this->role;}


    // Setter
    public function setId(?int $id) { $this->id = $id; }
    public function setName(?string $name) { $this->name = $name; }
    public function setSurname(?string $surname) { $this->surname = $surname; }
    public function setEmail(?string $email) { $this->email = $email; }
    public function setPassword(?string $password) { $this->password = $password; }
    public function setRole(?Role $role) { $this->role = $role; }

    // Other function
    public function toString(): ?string {return $this->name ." ".$this->surname;}

}
