<?php
class Zopim_Livechat_AdminController extends Mage_Adminhtml_Controller_Action
{
    public function accountconfigAction()
    {
		$this->loadLayout()
			->_addContent($this->getLayout()->createBlock('livechat/accountconfig'))
			->renderLayout();
    }

    public function dashboardAction()
    {
		$this->loadLayout()
			->_addContent($this->getLayout()->createBlock('livechat/dashboard'))
			->renderLayout();
    }

    public function customizationAction()
    {
		$this->loadLayout()
			->_addContent($this->getLayout()->createBlock('livechat/customization'))
			->renderLayout();
    }

    public function instantmessagingAction()
    {
		$this->loadLayout()
			->_addContent($this->getLayout()->createBlock('livechat/instantmessaging'))
			->renderLayout();
    }

    public function indexAction()
    {
       accountconfigAction();
    }
}
