<?php
class Rack_SelfDelete_Block_Order_View extends Mage_Sales_Block_Order_View
{
    protected $_secret_key = null;
    protected function _construct()
    {
        parent::_construct();
        $_session = Mage::getSingleton("selfdelete/session");
        $_secretkey = Mage::helper('selfdelete')->getSecretKey();
        $_session->setSecretKey($_secretkey);
        $this->setSecretKey($_secretkey);
        
        $this->setTemplate('selfdelete/order/view.phtml');
    }

    public function getCancelActionUrl()
    {
        if ($this->getOrder()->canCancel() && 
            $this->getOrder()->getState() === Mage_Sales_Model_Order::STATE_NEW) {
            $param = array ('order_id' => $this->getOrder()->getId());
            return Mage::getUrl('selfdelete/order/cancanelorder', $param) . "key/" . $this->getSecretKey() . "/";    
        }
    }

    public function canCancel()
    {
        if (Mage::getStoreConfig('selfdelete/selfdelete/candeleteorder') === 'true' &&
            $this->getOrder()->canCancel() && 
            $this->getOrder()->getState() === Mage_Sales_Model_Order::STATE_NEW) {
            return true;    
            }
        return false;
    }
}
