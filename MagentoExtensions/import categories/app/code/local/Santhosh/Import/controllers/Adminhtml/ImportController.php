<?php

class Santhosh_Import_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction() {
		$this->_title(Mage::helper('santhosh_import')->__('Import Profiles'));

		$this->loadLayout()->_setActiveMenu('santhosh_core/import');
		$this->renderLayout();
	}

	public function categoryAction() {
		$type = $this->getRequest()->getParam('type');
		$this->_title(Mage::helper('santhosh_import')->__('Import Category Profiles'));

		if ($this->getRequest()->isPost()) {
			$this->getResponse()->setBody($this->getLayout()->createBlock('santhosh_import/adminhtml_category_import_'.$type)->toHtml());
			$this->getResponse()->sendResponse();
		} else {
			$this->loadLayout()->_setActiveMenu('santhosh_core/import');
			$this->renderLayout();
		}
	}

}