<?php

require_once("include/model/RoleService.php");

class RoleServiceProxy extends RoleService {

    private ?DataLayer $dataLayer;
    private int $roleId;
    private int $serviceId;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

    public function getRoleId(): int { return $this->roleId; }
    public function getServiceId(): int { return $this->serviceId; }

    public function setRoleId(int $roleId): void { $this->roleId = $roleId; }
    public function setServiceId(int $serviceId): void { $this->serviceId = $serviceId; }

    public function loadServicesForRole(): array {
        return ($this->dataLayer)->getRoleServiceDAO()->getServicesByRole($this->roleId);
    }
}
