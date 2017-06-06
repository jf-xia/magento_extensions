<?php
    class Click2Customer_Analytics_Helper_Data extends Mage_Core_Helper_Abstract {
        private $response = null;
        
        function setResponse($response) {
            $this->response = $response;
        }
        
        function getResponse() {
            return $this->response;
        }
    }
