<?php 
/* 
NextWidgets **NOTICE OF LICENSE** 
This source file is subject to the EULA that is bundled with this package in the file LICENSE.pdf. It is also available through the world-wide-web at this URL:
http://nextwidgets.com/magento_extension_license.pdf
=================================================================
MAGENTO COMMUNITY EDITION USAGE NOTICE
=================================================================
This package is designed for the Magento COMMUNITY edition
This extension may not work on any other Magento edition except Magento COMMUNITY edition. NextWidgets does not provide extension support in case of incorrect edition usage.
=================================================================
Copyright (c) 2011 NextWidgets – ALENSA AG (http://www.nextwidgets.com)
License http://nextwidgets.com/magento_extension_license.pdf
*/ 
?>
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
class Kanavan_Searchautocomplete_Model_Source_Effect extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
	{

    /**
     * Options getter
     *
     * @return array
     */
	 public function getAllOptions()
    {
    	$this->toOptionArray();
      	
	}
    public function toOptionArray()
    {
        return array(
            array('value' => '', 'label'=>Mage::helper('adminhtml')->__('Chose effect...')),
            array('value' => 'toogle', 'label'=>Mage::helper('adminhtml')->__('Toogle')),
            array('value' => 'show_hide', 'label'=>Mage::helper('adminhtml')->__('Show/Hide')),
            array('value' => 'face', 'label'=>Mage::helper('adminhtml')->__('Face')),           
            
            );
        
    }

}
