<?php

abstract class Wee_Fpc_Model_Processor_Abstract
{
    protected function _replaceContent($key, $replace, $content)
    {
        return preg_replace(Mage::helper('wee_fpc')->getPattern($key), $replace, $content);
    }
}