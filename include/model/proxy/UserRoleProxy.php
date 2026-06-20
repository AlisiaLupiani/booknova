<?php

require_once("include/model/UserRole.php");

class UserRoleProxy extends UserRole {

    private ?DataLayer $dataLayer;
    private int $userId;
    private int $roleId;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

    public function getUserId(): int { return $this->userId; }
    public function getRoleId(): int { return $this->roleId; }

    public function setUserId(int $userId): void { $this->userId = $userId; }
    public function setRoleId(int $roleId): void { $this->roleId = $roleId; }

    public function loadRolesForUser(): array {
        return ($this->dataLayer)->getUserRoleDAO()->getRolesByUser($this->userId);
    }
}
