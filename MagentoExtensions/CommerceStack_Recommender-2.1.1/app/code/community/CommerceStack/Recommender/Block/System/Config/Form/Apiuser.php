<?php
class CommerceStack_Recommender_Block_System_Config_Form_Apiuser extends Mage_Adminhtml_Block_System_Config_Form_Field
{  
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $account = Mage::getModel('csapiclient/account');
        $key = $account->getApiKey();
        $apiUser = $key['user'];

        if(!$element->getValue())
        {
            $element->setValue($apiUser);
        }

        return parent::_getElementHtml($element);
    }
}