<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Pinterest
 * @version    1.0.4
 * @copyright  Copyright (c) 2012 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */


class Magpleasure_Pinterest_Model_System_Config_Source_Type extends Mage_Core_Block_Template
{
    protected function _helper()
    {
        return Mage::helper('mppinterest');
    }

    public function toOptionArray()
    {
        return array(
            array('value'=>'horizontal', 'label'=> $this->_helper()->__("Horizontal")),
            array('value'=>'vertical', 'label'=> $this->_helper()->__("Vertical")),
            array('value'=>'none', 'label'=> $this->_helper()->__("No Count")),
        );
    }
}