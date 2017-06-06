<?php
class CommerceStack_Recommender_Block_System_Config_Form_Helpiframe extends Mage_Adminhtml_Block_System_Config_Form_Field
{  
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $server = Mage::getModel('csapiclient/server');
        $url = $server->base_url . 'help';

        // This will provision an account if none exists so the client should
        // have an account the first time the config page is loaded.
        $account = Mage::getModel('csapiclient/account');
        $url = $account->appendAuthToUri($url);

        try
        {
            $style = $server->get('/help/style');
        }
        catch(Exception $e)
        {
            // Credentials are probably messed up. Don't throw an exception here
            // so that user can see Api User value under Account for support
        }

        return '<iframe id="recommender_help_iframe" src="' . $url . '" style="' . trim($style) . '"></iframe>';

    }
}