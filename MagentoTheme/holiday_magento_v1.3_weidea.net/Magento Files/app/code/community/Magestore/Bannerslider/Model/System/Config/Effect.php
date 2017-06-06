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
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
class Magestore_Bannerslider_Model_System_Config_Effect
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'swing', 'label'=>Mage::helper('bannerslider')->__('swing')),
            array('value' => 'easeInQuad', 'label'=>Mage::helper('bannerslider')->__('easeInQuad')),
            array('value' => 'easeOutQuad', 'label'=>Mage::helper('bannerslider')->__('easeOutQuad')),
            array('value' => 'easeInOutQuad', 'label'=>Mage::helper('bannerslider')->__('easeInOutQuad')),
            array('value' => 'easeInCubic', 'label'=>Mage::helper('bannerslider')->__('easeInCubic')),
            array('value' => 'easeOutCubic', 'label'=>Mage::helper('bannerslider')->__('easeOutCubic')),
            array('value' => 'easeInOutCubic', 'label'=>Mage::helper('bannerslider')->__('easeInOutCubic')),
            array('value' => 'easeInQuart', 'label'=>Mage::helper('bannerslider')->__('easeInQuart')),
            array('value' => 'easeOutQuart', 'label'=>Mage::helper('bannerslider')->__('easeOutQuart')),
            array('value' => 'easeInOutQuart', 'label'=>Mage::helper('bannerslider')->__('easeInOutQuart')),
            array('value' => 'easeInQuint', 'label'=>Mage::helper('bannerslider')->__('easeInQuint')),
            array('value' => 'easeOutQuint', 'label'=>Mage::helper('bannerslider')->__('easeOutQuint')),
            array('value' => 'easeInOutQuint', 'label'=>Mage::helper('bannerslider')->__('easeInOutQuint')),
            array('value' => 'easeInSine', 'label'=>Mage::helper('bannerslider')->__('easeInSine')),
            array('value' => 'easeOutSine', 'label'=>Mage::helper('bannerslider')->__('easeOutSine')),
            array('value' => 'easeInOutSine', 'label'=>Mage::helper('bannerslider')->__('easeInOutSine')),
            array('value' => 'easeInExpo', 'label'=>Mage::helper('bannerslider')->__('easeInExpo')),
            array('value' => 'easeOutExpo', 'label'=>Mage::helper('bannerslider')->__('easeOutExpo')),
            array('value' => 'easeInOutExpo', 'label'=>Mage::helper('bannerslider')->__('easeInOutExpo')),
            array('value' => 'easeInCirc', 'label'=>Mage::helper('bannerslider')->__('easeInCirc')),
            array('value' => 'easeOutCirc', 'label'=>Mage::helper('bannerslider')->__('easeOutCirc')),
            array('value' => 'easeInOutCirc', 'label'=>Mage::helper('bannerslider')->__('easeInOutCirc')),
            array('value' => 'easeInElastic', 'label'=>Mage::helper('bannerslider')->__('easeInElastic')),
            array('value' => 'easeOutElastic', 'label'=>Mage::helper('bannerslider')->__('easeOutElastic')),
            array('value' => 'easeInOutElastic', 'label'=>Mage::helper('bannerslider')->__('easeInOutElastic')),
            array('value' => 'easeInBack', 'label'=>Mage::helper('bannerslider')->__('easeInBack')),
            array('value' => 'easeOutBack', 'label'=>Mage::helper('bannerslider')->__('easeOutBack')),
            array('value' => 'easeInOutBack', 'label'=>Mage::helper('bannerslider')->__('easeInOutBack')),
            array('value' => 'easeInBounce', 'label'=>Mage::helper('bannerslider')->__('easeInBounce')),
            array('value' => 'easeOutBounce', 'label'=>Mage::helper('bannerslider')->__('easeOutBounce')),
            array('value' => 'easeInOutBounce', 'label'=>Mage::helper('bannerslider')->__('easeInOutBounce')),
        );
    }
 
}
