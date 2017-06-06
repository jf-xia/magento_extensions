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

class AW_Blog_Helper_Post extends Mage_Core_Helper_Abstract {

	/**
	 * Renders CMS page
	 *
	 * Call from controller action
	 *
	 * @param Mage_Core_Controller_Front_Action $action
	 * @param integer $pageId
	 * @return boolean
	 */
	public function renderPage(Mage_Core_Controller_Front_Action $action, $identifier=null) {

        $page = Mage::getModel('blog/post');
		if (!is_null($identifier) && $identifier!==$page->getId()) {
			$page->setStoreId(Mage::app()->getStore()->getId());
			if (!$page->load($identifier)) {
				return false;
			}
		}

		if (!$page->getId()) {
			return false;
		}
		if ($page->getStatus() == 2) {
			return false;
		}
		$page_title = Mage::getSingleton('blog/post')->load($identifier)->getTitle();
		$blog_title = Mage::getStoreConfig('blog/blog/title') . " - ";

		$action->loadLayout();
		if ($storage = Mage::getSingleton('customer/session')) {
			$action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
		}
        if (Mage::getStoreConfig('blog/rss/enable'))
		{
            Mage::helper('blog')->addRss($action->getLayout()->getBlock('head'), Mage::getUrl(Mage::getStoreConfig('blog/blog/route')) . "rss");
		}
		$action->getLayout()->getBlock('head')->setTitle($blog_title . $page_title);
		$action->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('blog/blog/layout'));
		$action->renderLayout();

		return true;
	}

	public function closetags($html) {
		#put all opened tags into an array
		preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
		$openedtags = $result[1];

		#put all closed tags into an array
		preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
		$closedtags = $result[1];
		$len_opened = count ( $openedtags );
		# all tags are closed
		if( count ( $closedtags ) == $len_opened ) {
			return $html;
		}
		$openedtags = array_reverse ( $openedtags );
		# close tags
		for( $i = 0; $i < $len_opened; $i++ ) {
			if ( !in_array ( $openedtags[$i], $closedtags ) ) {
				$html .= "</" . $openedtags[$i] . ">";
			}
			else {
				unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
			}
		}
		return $html;
	}
}
