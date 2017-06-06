<?php
class CommerceStack_Recommender_Block_System_Config_Form_Email extends Mage_Adminhtml_Block_System_Config_Form_Field
{  
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $user = Mage::getSingleton('admin/session');
        $userEmail = $user->getUser()->getEmail();

        if(!$element->getValue())
        {
            $element->setValue($userEmail);

            // Probably should be done in setup but add this email
            // address to the proper config key
            Mage::getModel('core/config')->saveConfig('recommender/account/email', $userEmail);
        }

        return parent::_getElementHtml($element);
    }
}