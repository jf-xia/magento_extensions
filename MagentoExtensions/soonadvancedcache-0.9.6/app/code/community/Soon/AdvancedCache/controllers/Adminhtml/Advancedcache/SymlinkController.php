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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension
 * to newer versions in the future.
 *
 * @category   Netzarbeiter
 * @package    Netzarbeiter_Cache
 * @copyright  Copyright (c) 2011 Vinai Kopp http://netzarbeiter.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Soon_AdvancedCache_Adminhtml_AdvancedCache_SymlinkController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->_title($this->__('Cache'))->_title($this->__('Symlink Cache'));
        $this->loadLayout();
        $this->_setActiveMenu('system/advanced_cache');
        $this->renderLayout();
    }

    public function initSymlinksAction() {
        try {
            $results = Mage::helper('advancedcache/symlink')->initTagSymlinks()->getResults();
            $this->_getSession()->addSuccess($this->__('Created %s symlinks', count($results)));
            $this->_getSession()->setResultInfo($results);
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            Mage::logException($e);
        }
        $this->_redirect('*/*/index');
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('system/advanced_cache/symlink');
    }

}