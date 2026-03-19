<?php

class Order {
    private ?int $id;
    private ?int $userId;
    private ?int $paymentMethodId;
    private ?int $shippingMethodId;
    private ?string $orderDate;
    private ?float $total;

    public function __construct() {
        $this->id = null;
        $this->userId = null;
        $this->paymentMethodId = null;
        $this->shippingMethodId = null;
        $this->orderDate = '';
        $this->total = 0.0;
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }
    public function getPaymentMethodId(): ?int { return $this->paymentMethodId; }
    public function getShippingMethodId(): ?int { return $this->shippingMethodId; }
    public function getOrderDate(): ?string { return $this->orderDate; }
    public function getTotal(): ?float { return $this->total; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUserId(?int $userId): void { $this->userId = $userId; }
    public function setPaymentMethodId(?int $paymentMethodId): void { $this->paymentMethodId = $paymentMethodId; }
    public function setShippingMethodId(?int $shippingMethodId): void { $this->shippingMethodId = $shippingMethodId; }
    public function setOrderDate(?string $orderDate): void { $this->orderDate = $orderDate; }
    public function setTotal(?float $total): void { $this->total = $total; }
}