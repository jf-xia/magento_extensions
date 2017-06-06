<?php 

class CommerceStack_Recommender_Block_System_Config_Form extends Mage_Adminhtml_Block_System_Config_Form
{
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->removeItem('js', 'mage/adminhtml/loader.js');
        $this->getLayout()->getBlock('head')->addJs('commercestack/adminhtml/recommender.js');
        
        return parent::_prepareLayout();
    }
}