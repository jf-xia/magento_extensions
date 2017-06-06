<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_HomepageController extends Fishpig_Wordpress_Controller_Abstract
{
	/**
	 * Initialise the homepage
	 *
	 */
	protected function _init()
	{
		parent::_init();	
		$this->_addCanonicalLink(Mage::helper('wordpress')->getUrl());
		
		if ($this->getSeoPlugin()->isEnabled()) {
			if ($headBlock = $this->getLayout()->getBlock('head')) {
			
				foreach($this->getSeoPlugin()->getMetaFields() as $field) {
					if ($value = $this->getSeoPlugin()->getPluginOption('home_'.$field)) {
						$headBlock->setData($field, $value);
					}
				}

				if ($title = $this->getSeoPlugin()->getPluginOption('home_title')) {
					$this->_title()->_title($title);
				}
			}		
		}
		
		return true;
	}

	/**
	 * If not feed, display the blog homepage
	 *
	 */
	public function indexAction()
	{
		if ($this->isFeedPage()) {
			$this->_forward('index', 'feed');
		}
		else {
			parent::indexAction();
		}
	}
}
