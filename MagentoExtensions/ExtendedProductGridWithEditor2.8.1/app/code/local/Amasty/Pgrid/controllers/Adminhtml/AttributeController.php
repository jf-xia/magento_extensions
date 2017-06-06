<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Pgrid
*/
class Amasty_Pgrid_Adminhtml_AttributeController extends Mage_Adminhtml_Controller_Action
{
    public function saveAction()
    {
        $attributes = Mage::app()->getRequest()->getParam('pattribute', array());
        $config = Mage::getModel('core/config');
        $config->saveConfig('ampgrid/attributes/ongrid', implode(',', array_keys($attributes)));
        $config->cleanCache();
        
        $backUrl = Mage::app()->getRequest()->getParam('backurl');
        if (!$backUrl)
        {
            $backUrl = Mage::helper('core/url')->getUrl('adminhtml/catalog/product');
        }
        $this->getResponse()->setRedirect($backUrl);
    }
}