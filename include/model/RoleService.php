<?php

class RoleService {
    private ?int $roleId;
    private ?int $serviceId;

    public function __construct(?int $roleId = null, ?int $serviceId = null) {
        $this->roleId = $roleId;
        $this->serviceId = $serviceId;
    }

    // Getters
    public function getRoleId(): ?int { return $this->roleId; }
    public function getServiceId(): ?int { return $this->serviceId; }

    // Setters
    public function setRoleId(?int $roleId): void { $this->roleId = $roleId; }
    public function setServiceId(?int $serviceId): void { $this->serviceId = $serviceId; }

    public function toString(): string {
        return "Role {$this->roleId} → Service {$this->serviceId}";
    }
}
