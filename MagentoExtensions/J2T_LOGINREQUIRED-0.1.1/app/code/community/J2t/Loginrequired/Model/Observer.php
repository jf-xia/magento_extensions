<?php
    class J2t_Loginrequired_Model_Observer{
        public function __construct(){
        }
        public function checklogin($observer){
            $activated = Mage::getStoreConfig('customer/loginrequired/activated', Mage::app()->getStore()->getId());
            if ($activated){
                $uri = Mage::app()->getRequest()->getRequestUri();
                $redirect = true;

                switch (true) {
                    case preg_match("/\/customer\/account/i", $uri):
                        $redirect = false;
                        break;
                    case preg_match("/\/customer\/account\/login\//i", $uri):
                        $redirect = false;
                        break;
                    case preg_match("/\/customer\/account\/create\//i", $uri):
                        $redirect = false;
                        break;
                    case preg_match("/\/customer\/account\/index\//i", $uri):
                        $redirect = false;
                        break;
                    case preg_match("/\/customer\/account\/forgotpassword\//i", $uri):
                        $redirect = false;
                        break;
                }
                if ($redirect && !Mage::getSingleton('customer/session')->isLoggedIn()){
                    $redirect_url = Mage::getUrl('customer/account/login');
                    header("Location: {$redirect_url}");
                    exit;
                }                
            }
            
        }
    }