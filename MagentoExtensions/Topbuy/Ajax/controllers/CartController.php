<?php
require_once 'Mage/Checkout/controllers/CartController.php';
class Topbuy_Ajax_CartController extends Mage_Checkout_CartController
{   
    public function PostageAction() {   
//        $weight = $this->_getQuote()->getShippingAddress()->getWeight();
        $postcode   = (string) $this->getRequest()->getParam('postcode');
        $rateship = Mage::getModel('matrixrate/carrier_matrixrate')->get_pro_ship();
        echo $rateship;
        
        
    }

    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                Mage::getSingleton('checkout/cart')->removeItem($id)
                  ->save();
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        $this->_redirect('checkout/onepage');
    }

}