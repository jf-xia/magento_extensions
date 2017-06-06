<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Cms
 * @copyright  Copyright (c) 2004-2007 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Cms page helper
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Monk_Blog_Helper_Comment extends Mage_Core_Helper_Abstract
{
     public function renderPage(Mage_Core_Controller_Front_Action $action, $identifier=null, $data)
    {
        $page = Mage::getSingleton('blog/post');
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
		$action->getLayout()->getBlock('head')->setTitle($blog_title . $page_title);
		$action->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('blog/blog/layout'));
		$action->getLayout()->getBlock('post')->setCommentDetails($data['user'], $data['email'], $data['comment']);
        $action->renderLayout();

        return true;
    }
}