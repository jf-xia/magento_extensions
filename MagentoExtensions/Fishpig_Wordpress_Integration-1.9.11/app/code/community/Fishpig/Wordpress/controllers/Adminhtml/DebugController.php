<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Adminhtml_DebugController extends Mage_Adminhtml_Controller_Action
{	
	public function indexAction()
	{
		try {
			if (Mage::helper('wordpress/debug')->isIntegrated()) {
				if ($Mage::helper('wordpress')->isFullyIntegrated()) {
					Mage::getSingleton('adminhtml/session')->addSuccess(Fishpig_Wordpress_Helper_Debug::SUCCESS_FULLY_INTEGRATED);
					
					Mage::getSingleton('adminhtml/session')->addSuccess(
						sprintf(
							Fishpig_Wordpress_Helper_Debug::SUCCESS_FULLY_INTEGRATED_LINKS, Mage::helper('wordpress')->getUrl(), Mage::helper('wordpress')->getAdminUrl()
							)
						);
				}
				else {
					Mage::getSingleton('adminhtml/session')->addSuccess(Fishpig_Wordpress_Helper_Debug::SUCCESS_SEMI_INTEGRATED);
				}
			}
			else {
				Mage::getSingleton('adminhtml/session')->addWarning('It is difficult to determine whether Magento and Wordpress are integrated. Please submit an error report to the module developers.');
				Mage::helper('wordpress')->log('debug.indexAction: execution escaped the correct flow');
			}
		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		
		$this->_redirect('adminhtml/system_config/edit/section/wordpress');
	}
}

