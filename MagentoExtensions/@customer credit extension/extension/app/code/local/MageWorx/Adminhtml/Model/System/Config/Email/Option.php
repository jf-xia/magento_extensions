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
 * @package    MageWorx_Adminhtml
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * MageWorx Adminhtml extension
 *
 * @category   MageWorx
 * @package    MageWorx_Adminhtml
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_Adminhtml_Model_System_Config_Email_Option
{
    protected $_options;

    public function toOptionArray($isMultiselect = false, $storeId = null)
    {
    	$keys = array(
    		'ident_custom1',
    		'ident_custom2',
    		'ident_general',
    		'ident_sales',
    		'ident_support',
    	);
    	$i = 0;
    	foreach ($keys as $value) {
    		$name  = Mage::getStoreConfig('trans_email/'.$value.'/name', $storeId);
        	$email = Mage::getStoreConfig('trans_email/'.$value.'/email', $storeId);
    		if ($name && $email) {
    			$this->_options[$i]['label'] = Mage::helper('linkexchange')->htmlEscape($name);
        		$this->_options[$i]['value'] = $email;
        		$i++;
    		}
    	}

    	$options = $this->_options;
        if ($isMultiselect) {
            array_unshift($options, array('value' => '', 'label' => Mage::helper('adminhtml')->__('--Please Select--')));
        }
        return $options;
    }
}