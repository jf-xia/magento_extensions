<?php 
/**
 * Anais_Breadcrumbs extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Anais
 * @package    Anais_Breadcrumbs
 * @copyright  Copyright (c) 2011 Anais Software Services
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */ 
 /**
 * @category   Anais
 * @package    Anais_Breadcrumbs
 * @author     Marius Strajeru <marius.strajeru@anais-it.com>
 */ 
class Anais_Breadcrumbs_Model_Adminhtml_Observer{
/**
	 * add js block to edit form
	 * @access pubic
	 * @param Varien_Event_Observer $observer
	 * @return Anais_Breadcrumbs_Model_Adminhtml_Observer
	 * @author Marius Strajeru <marius.strajeru@anais-it.com>
	 */
	public function addConfigJsBlock($observer){
		$event = $observer->getEvent();
		$controller = $event->getAction()->getRequest();
		if ($controller->getModuleName() == 'admin' && $controller->getControllerName() == 'system_config' && $controller->getActionName() == 'edit'){
			if ($event->getLayout()->getBlock('js')){
				$block = $event->getLayout()->createBlock('adminhtml/template')->setTemplate('anais_breadcrumbs/config/js.phtml');
				$event->getLayout()->getBlock('js')->append($block);
			}
		}
		return $this;
	}
}