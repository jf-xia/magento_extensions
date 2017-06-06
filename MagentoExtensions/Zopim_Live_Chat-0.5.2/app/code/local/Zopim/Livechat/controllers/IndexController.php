<?php
class Zopim_Livechat_IndexController extends Mage_Core_Controller_Front_Action
{
 
    public function indexAction()
    {
	$this->loadLayout();
	$this->getLayout()
		->getBlock('content')->append(
			$this->getLayout()->createBlock('livechat/index')
    	);
        $this->renderLayout();
    }
}
