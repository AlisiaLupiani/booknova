<?php

require_once("include/model/Service.php");

class ServiceProxy extends Service {

    private ?DataLayer $dataLayer;
    private int $serviceId;

    public function __construct(?DataLayer $dataLayer) {
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

    public function getServiceId(): int { return $this->serviceId; }
    public function setServiceId(int $serviceId): void { $this->serviceId = $serviceId; }

    public function loadService(): ?Service {
        if (parent::getName() == '' && $this->serviceId > 0) {
            $service = ($this->dataLayer)->getServiceDAO()->getById($this->serviceId);

            if ($service !== null) {
                parent::setId($service->getId());
                parent::setName($service->getName());
            }
        }

        return parent::getName() !== '' ? $this : null;
    }
}
