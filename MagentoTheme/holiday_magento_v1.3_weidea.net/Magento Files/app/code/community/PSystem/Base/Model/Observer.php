<?php

class PSystem_Base_Model_Observer {
/**
 * Observer post dispatching
 * 
 * @param Varien_Event_Observer $event
 */
	public function postdispatch(Varien_Event_Observer $event) {
		/* @var $controller Mage_Core_Controller_Varien_Action */
		$controller = $event->getControllerAction();
		if (!$controller->getRequest()->getHeader('X-Requested-With')) {
			return;
		}
		
		$param = array();
		
		if (function_exists('apache_request_headers'))
			$headers = apache_request_headers();
		elseif (function_exists('getallheader'))
			$headers = getallheader();
		else
			$headers = $_SERVER;
		foreach ($headers as $headerName => $headerValue) {
			$headerName = strtolower($headerName);
			if (!preg_match('/pascalsystem(.*)/',$headerName,$regs))
				continue;
			$param[str_replace('_','.',$regs[1])] = $headerValue;
		}
		
		//orginal magento ajax request
		if (!count($param)) {
			return;
		}
		
		$layout = Mage::app()->getLayout();
		$blocks = array();
		foreach ($param as $blockName => $selector) {
			$temp = $layout->getBlock($blockName);
			$blocks[$blockName] = array(
				'selector'	=> $selector,
				'html'		=> ($temp)?$temp->toHtml():''
			);
		}
		echo json_encode($blocks);
		exit;
	}
}