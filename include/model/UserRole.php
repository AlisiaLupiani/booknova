<?php

class UserRole {
    private ?int $userId;
    private ?int $roleId;

    public function __construct(?int $userId = null, ?int $roleId = null) {
        $this->userId = $userId;
        $this->roleId = $roleId;
    }

    // Getters
    public function getUserId(): ?int { return $this->userId; }
    public function getRoleId(): ?int { return $this->roleId; }

    // Setters
    public function setUserId(?int $userId): void { $this->userId = $userId; }
    public function setRoleId(?int $roleId): void { $this->roleId = $roleId; }

    public function toString(): string {
        return "User {$this->userId} → Role {$this->roleId}";
    }
}
