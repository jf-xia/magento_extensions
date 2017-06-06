<?php
class TM_FacebookLB_Adminhtml_Model_System_Config_Source_Font
{
     public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('facebooklb')->__('')),
            array('value'=>'arial', 'label'=>Mage::helper('facebooklb')->__('arial')),
            array('value'=>'lucida grande', 'label'=>Mage::helper('facebooklb')->__('lucida grande')),
            array('value'=>'segoe ui', 'label'=>Mage::helper('facebooklb')->__('segoe ui')),
            array('value'=>'tahoma', 'label'=>Mage::helper('facebooklb')->__('tahoma')),
            array('value'=>'trebuchet ms', 'label'=>Mage::helper('facebooklb')->__('trebuchet ms')),
            array('value'=>'verdana', 'label'=>Mage::helper('facebooklb')->__('verdana'))
        );
    }
}