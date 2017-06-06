<?php

class Santhosh_Export_Adminhtml_ExportController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction() {
		$this->_title(Mage::helper('santhosh_export')->__('Export Profiles'));

		$this->loadLayout()->_setActiveMenu('santhosh_core/export');
		$this->renderLayout();
	}

	public function categoryAction() {
		$type = $this->getRequest()->getParam('type');
		$this->_title(Mage::helper('santhosh_export')->__('Export Category Profiles'));
		$this->getResponse()->setBody($this->getLayout()->createBlock('santhosh_export/adminhtml_category_export_'.$type)->toHtml());
		$this->getResponse()->sendResponse();
	}

}