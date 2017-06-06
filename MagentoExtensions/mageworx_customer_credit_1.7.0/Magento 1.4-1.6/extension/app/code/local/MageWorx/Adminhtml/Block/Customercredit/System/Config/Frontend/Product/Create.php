<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2011 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_Adminhtml_Block_Customercredit_System_Config_Frontend_Product_Create extends Mage_Adminhtml_Block_System_Config_Form_Field
{    
    
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {        
        $html = $element->getElementHtml();
        $this->setElement($element);
        return $html . '<br/><br/>' . $this->_getAddRowButtonHtml($element->getValue());
    }

    protected function _getAddRowButtonHtml($sku) {        
        $productId = false;
        if (!$sku) $sku = 'customercredit';        
        $productId = Mage::getModel('catalog/product')->setStoreId(Mage::app()->getStore()->getId())->getIdBySku($sku);
        
        $title = ($productId?$this->__('Edit Product'):$this->__('Create Product'));
        
        $buttonBlock = $this->getElement()->getForm()->getParent()->getLayout()->createBlock('adminhtml/widget_button');

        $_websiteCode = $buttonBlock->getRequest()->getParam('website');
        $params = array(
            'website' => $_websiteCode,
            // We add _store for the base url function, otherwise it will not correctly add the store code if configured
            '_store' => $_websiteCode ? $_websiteCode : Mage::app()->getDefaultStoreView()->getId()
        );

        // TODO: for real multi-store self-testing, the test button (and other configuration options) 
        // should probably be set to show in website. Currently they are not.
        
        if ($productId) {
            $url = Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product/edit/', array('id'=>$productId));            
        } else {
            $url = Mage::helper('adminhtml')->getUrl('mageworx/customercredit_credit/createProduct/');
        }

        return $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setLabel($this->__($title))
                ->setOnClick("window.location.href='" . $url . "'")
                ->toHtml();
    }

}