<?php

/**
 * offsiteorder Info Block
 *
 * @category   NeedTool
 * @package    NeedTool_OffsiteOrder
 * @name       NeedTool_OffsiteOrder_Block_Info
 * @author     NeedTool.com <cs@needtool.com>
 */
class NeedTool_OffsiteOrder_Block_Info extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('needtooloffsiteorder/info.phtml');
    }
     
    public function getOrder()
    {		
        return Mage::registry('current_order');
    }

    public function addLink($name, $path, $label)
    {
        $this->_links[$name] = new Varien_Object(array(
            'name' => $name,
            'label' => $label,
            'url' => empty($path) ? '' : Mage::getUrl($path, array('order_id' => $this->getOrder()->getId()))
        ));
        return $this;
    }
    
    public function getPayUrl()
    {
    	//TODO check order status
    	//Mage::log($this->getOrder());
    	//$orderId=$this->getOrder()->getRealOrderId();
    	$orderId = '';
    	if (!$this->getOrder()){
    		$orderId=Mage::getSingleton('checkout/session')->getLastRealOrderId();
    	}else{
    		$orderId=$this->getOrder()->getRealOrderId();
			}    	
        return $this->getUrl('paymentstub/', array(
        	'_secure' => true,
        	//'order_id' => $this->getOrder()->getId()
        ))
        .'?realorderid='.$orderId
        ;
    }

}