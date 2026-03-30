<?php

require_once('include/model/ShippingMethod.php');

class ShippingMethodProxy extends ShippingMethod{

        private ?DataLayer $dataLayer;

        public function __construct(?DataLayer $dataLayer){
            parent::__construct();
            $this->dataLayer = $dataLayer;

        }
    
      

    
    

        
}

