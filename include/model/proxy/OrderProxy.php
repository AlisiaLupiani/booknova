<?php

include_once('include/model/Order.php');

class OrderProxy extends Order{

    private ?DataLayer $dataLayer;

    private ?int $userId;
    private ?int $paymentMethodId;
    private ?int $shippingMethodId;

    public function __construct(?DataLayer $dataLayer){
        parent::__construct();
        $this->dataLayer = $dataLayer;
    }

    public function getUserId(): ?int { return $this->userId; }
    public function setUserId(?int $userId) { return $this->userId = $userId; }

    public function getPaymentMethodId(): ?int { return $this->paymentMethodId; }
    public function setpaymentMethodId(?int $paymentMethodId) { return $this->paymentMethodId = $paymentMethodId; }

    public function getShippingMethodId(): ?int { return $this->shippingMethodId; }
    public function setShippingMethodId(?int $shippingMethodId) { return $this->shippingMethodId = $shippingMethodId; }


    public function getUser(): ?User{
        if(parent:: getUser() == null && $this->userId > 0 ){
            parent:: setUser((($this -> dataLayer)->getUserDao())->getUserById($this->userId));
        }
        return parent::getUser();
    }

    public function getPaymentMethod(): ?PaymentMethod{
        if(parent:: getPaymentMethod() == null && $this->paymentMethodId > 0 ){
            parent:: setPaymentMethod((($this -> dataLayer)->getPaymentMethodDao())->getPaymentMethodById($this->paymentMethodId));
        }
        return parent::getPaymentMethod();
    }
    
    public function getShippingMethod(): ?ShippingMethod{
        if(parent:: getShippingMethod() == null && $this->shippingMethodId > 0 ){
            parent:: setShippingMethod((($this -> dataLayer)->getShippingMethodDao())->getShippingMethodById($this->shippingMethodId));
        }
        return parent::getShippingMethod();
    }

}