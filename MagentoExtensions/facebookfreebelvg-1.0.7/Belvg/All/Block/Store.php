<?php


class Belvg_All_Block_Store extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{

    protected $_dummyElement;
    protected $_fieldRenderer;
    protected $_values;

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return '<div id="' . $element->getId() . '">'.$this->getContent().'</div>';
        return $html;
    }
    
    private function getContent() { 
        $data = null;
        $url = 'http://belvg.com/promoadmin/fbfree.xml';

        $data = file_get_contents($url);
        return $data;
    }
}
