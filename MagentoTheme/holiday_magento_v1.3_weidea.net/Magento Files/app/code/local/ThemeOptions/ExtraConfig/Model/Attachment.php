<?php class ThemeOptions_ExtraConfig_Model_Attachment
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('ExtraConfig')->__('Select')),
            array('value'=>'fixed', 'label'=>Mage::helper('ExtraConfig')->__('fixed')),
            array('value'=>'scroll', 'label'=>Mage::helper('ExtraConfig')->__('scroll'))     
        );
    }

}?>