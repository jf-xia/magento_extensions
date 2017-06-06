<?php
/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
 /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 *****************************************/
 /* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
 /***************************************
 *         DISCLAIMER   *
 *****************************************/
 /* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 *****************************************************
 * @category   Belvg
 * @package    Belvg_FacebookFree
 * @copyright  Copyright (c) 2010 - 2011 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_FacebookFree_Model_System_Config_Source_Font
{
    public function toOptionArray()
    {
        return array(
            //array('value'=>'', 'label'=>''),
            array('value'=>'arial', 'label'=>Mage::helper('facebookall')->__('Arial')),
            array('value'=>'lucida grande', 'label'=>Mage::helper('facebookall')->__('Lucida Grande')),
            array('value'=>'segoe ui', 'label'=>Mage::helper('facebookall')->__('Segoe Ui')),
            array('value'=>'tahoma', 'label'=>Mage::helper('facebookall')->__('Tahoma')),
            array('value'=>'trebuchet ms', 'label'=>Mage::helper('facebookall')->__('Trebuchet MS')),
            array('value'=>'verdana', 'label'=>Mage::helper('facebookall')->__('Verdana')),
        );
    }
}