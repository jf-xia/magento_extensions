<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-L.txt
 *
 * @category   AW
 * @package    AW_Blog
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class AW_Blog_IndexController extends Mage_Core_Controller_Front_Action {
    public function indexAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('blog/blog/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('blog/blog/title'));
			$head->setKeywords(Mage::getStoreConfig('blog/blog/keywords'));
			$head->setDescription(Mage::getStoreConfig('blog/blog/description'));
			if (Mage::getStoreConfig('blog/rss/enable')) {
				$route = Mage::helper('blog')->getRoute();
				Mage::helper('blog')->addRss($head, Mage::getUrl($route) . "rss");
			}
		}
		$this->renderLayout();
	}

	public function listAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('blog/blog/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('blog/blog/title'));
			$head->setKeywords(Mage::getStoreConfig('blog/blog/keywords'));
			$head->setDescription(Mage::getStoreConfig('blog/blog/description'));
			if (Mage::getStoreConfig('blog/rss/enable')) {
				$route = Mage::helper('blog')->getRoute();
				if($tag = $this->getRequest()->getParam('tag')) {
					Mage::helper('blog')->addRss($head, Mage::getUrl($route) . "tag/$tag/" . "rss");
				}else {
					Mage::helper('blog')->addRss($head, Mage::getUrl($route) . "rss");
				}
			}
		}
		$this->renderLayout();
	}
}
