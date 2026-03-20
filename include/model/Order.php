<?php

require_once('./User.php');
require_once('./PaymentMethod.php');
require_once('./ShippingMethod.php');


class Order {
    private ?int $id;
    private ?User $user;
    private ?PaymentMethod $paymentMethod;
    private ?ShippingMethod $shippingMethod;
    private ?string $orderDate;
    private ?float $total;

    public function __construct() {
        $this->id = null;
        $this->user = null;
        $this->paymentMethod = null;
        $this->shippingMethod = null;
        $this->orderDate = '';
        $this->total = 0.0;
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function getPaymentMethod(): ?PaymentMethod { return $this->paymentMethod; }
    public function getShippingMethod(): ?ShippingMethod { return $this->shippingMethod; }
    public function getOrderDate(): ?string { return $this->orderDate; }
    public function getTotal(): ?float { return $this->total; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUser(?User $user): void { $this->user = $user; }
    public function setPaymentMethod(?PaymentMethod $paymentMethod): void { $this->paymentMethod = $paymentMethod; }
    public function setShippingMethod(?ShippingMethod $shippingMethod): void { $this->shippingMethod = $shippingMethod; }
    public function setOrderDate(?string $orderDate): void { $this->orderDate = $orderDate; }
    public function setTotal(?float $total): void { $this->total = $total; }



    }

