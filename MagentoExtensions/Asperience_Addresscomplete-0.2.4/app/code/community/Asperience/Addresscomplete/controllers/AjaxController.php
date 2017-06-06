<?php
/**
 * @category   ASPerience
 * @package    Asperience_Addresscomplete
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Addresscomplete_AjaxController extends Mage_Core_Controller_Front_Action
{
	
	public function suggestAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('addresscomplete/autocomplete')->toHtml());
    }
    
	public function countryAction()
    {
    	if($idCountry = $this->getRequest()->getPost('country')){
	    	$this->getResponse()->setBody(Mage::helper('addresscomplete')->verifCountry($idCountry));
    	}
    }
    
}