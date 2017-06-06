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
 * *************************************** */
/* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
  /***************************************
 *         DISCLAIMER   *
 * *************************************** */
/* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 * ****************************************************
 * @category   Belvg
 * @package    Belvg_Halloween
 * @author Pavel Novitsky <pavel@belvg.com>
 * @copyright  Copyright (c) 2010 - 2013 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

/**
 * Block position
 */
class Belvg_Halloween_Model_System_Config_Source_Event
{

    /**
     * Get block positions
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
                array('value' => 'cyberday', 'label' => Mage::helper('halloween')->__('Cyber Day')),
                array('value' => 'easterday', 'label' => Mage::helper('halloween')->__('Easter')),
                array('value' => 'halloween', 'label' => Mage::helper('halloween')->__('Halloween')),
                array('value' => 'independenceday', 'label' => Mage::helper('halloween')->__('Independence Day')),
                array('value' => 'laborday', 'label' => Mage::helper('halloween')->__('Labor Day')),
                array('value' => 'newyear', 'label' => Mage::helper('halloween')->__('New Year')),
                array('value' => 'patricksday', 'label' => Mage::helper('halloween')->__('Saint Patrick\'s Day')),
                array('value' => 'thanksgivingday', 'label' => Mage::helper('halloween')->__('Thanksgiving')),
                array('value' => 'valentinesday', 'label' => Mage::helper('halloween')->__('Valentine\'s Day')),
        );
    }

}
