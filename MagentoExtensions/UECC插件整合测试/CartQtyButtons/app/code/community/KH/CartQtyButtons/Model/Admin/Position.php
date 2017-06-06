<?php
/**
 * KH_CartQtyButtons_Model_Admin_Position
 * 
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * Selectbox entries
 * 
 * @category  	KH
 * @package    	KH_CartQtyButtons
 * @copyright  	Copyright (c) 2011 <info@kevinhorst.de> - KevinHorst IT
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      KevinHorst IT <info@kevinhorst.de>
 */

class KH_CartQtyButtons_Model_Admin_Position
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'both_left', 'label'=>Mage::helper('CartQtyButtons')->__('both left')),
        	array('value'=>'both_right', 'label'=>Mage::helper('CartQtyButtons')->__('both right')),
        	array('value'=>'one_left_one_right', 'label'=>Mage::helper('CartQtyButtons')->__('one left, one right')),
        );
    }

}
