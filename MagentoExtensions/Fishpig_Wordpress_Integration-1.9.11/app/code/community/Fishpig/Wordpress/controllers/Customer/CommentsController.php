<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Customer_CommentsController extends Mage_Core_Controller_Front_Action
{
	public function preDispatch()
	{
		parent::preDispatch();
	
		if (!$this->getRequest()->isDispatched()) {
			return;
		}
	
		if (!Mage::getSingleton('customer/session')->authenticate($this)) {
			$this->setFlag('', 'no-dispatch', true);
		}
	}
	
	public function listAction()
	{
		if ($this->isEnabledForStore()) {
			$this->loadLayout();
			$this->renderLayout();
		}
		else {
			$this->_forward('noRoute');
		}
	}
	
	/**
	 * Determine whether the extension has been enabled for the current store
	 *
	 * @return bool
	 */
	public function isEnabledForStore()
	{
		return !Mage::getStoreConfigFlag('advanced/modules_disable_output/Fishpig_Wordpress');
	}
}

