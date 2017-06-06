<?php

class NeedTool_OffsiteOrder_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
    protected $_code  = 'offsiteorder_payment';
    //protected $_formBlockType = 'offsiteorder/form';
    protected $_infoBlockType = 'offsiteorder/info';

    // Payment configuration
    protected $_isGateway               = false;
    protected $_canAuthorize            = false;
    protected $_canCapture              = false;
    protected $_canCapturePartial       = false;
    protected $_canRefund               = false;
    protected $_canVoid                 = false;
    protected $_canUseInternal          = true;
    protected $_canUseCheckout          = false;
    protected $_canUseForMultishipping  = false;
    
    /**
     * Assign data to info model instance
     *
     * @param   mixed $data
     * @return  Mage_Payment_Model_Method_Purchaseorder
     */
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
    	Mage::log("in");

        $this->getInfoInstance()->setPoNumber($data->getPoNumber());
        return $this;
    }

    // Order instance
    protected $_order = null;

    /**
     *  Returns Target URL
     *
     *  @return string Target URL
     */
    public function getoffsiteorderUrl()
    {
        $url = $this->getConfigData('transport').'://'.$this->getConfigData('gateway');
        return $url;
    }

    /**
     *  Return back URL
     *
     *  @return	  string URL
     */
	public function getPayURL()
	{
		return Mage::getUrl('offsiteorder/payment/normal', array('_secure' => true));
	}

    /**
     *  Form block description
     *
     *  @return	 object
     */
    public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock('offsiteorder/form_payment', $name);
        $block->setMethod($this->_code);
        $block->setPayment($this->getPayment());

        return $block;
    }

}