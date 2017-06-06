<?php

class Wee_Fpc_Block_Adminhtml_Additional extends Mage_Adminhtml_Block_Template
{
    public function getFlushCacheUrl()
    {
        return $this->getUrl('*/fpc/clean');
    }

    public function isEnabled()
    {
        return Mage::helper('wee_fpc')->isEnabled();
    }
}
