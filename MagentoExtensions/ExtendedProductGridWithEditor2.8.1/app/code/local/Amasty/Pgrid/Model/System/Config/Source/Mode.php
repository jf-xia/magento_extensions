<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Pgrid
*/
class Amasty_Pgrid_Model_System_Config_Source_Mode
{
    public function toOptionArray()
    {
        $options = array(
            array( 'value'  => 'single', 'label' => Mage::helper('ampgrid')->__('Single Cell') ),
            array( 'value'  => 'multi', 'label' => Mage::helper('ampgrid')->__('Multi Cell') ),
        );
        return $options;
    }
}