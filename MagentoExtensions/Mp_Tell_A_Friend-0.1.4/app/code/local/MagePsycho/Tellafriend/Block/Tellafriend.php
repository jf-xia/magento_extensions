<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Tellafriend
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Tellafriend_Block_Tellafriend extends Mage_Core_Block_Template
{
    public function getSuccessMessage()
    {
        $message = Mage::getSingleton('tellafriend/session')->getSuccess();
        return $message;
    }

    public function getErrorMessage()
    {
        $message = Mage::getSingleton('tellafriend/session')->getError();
        return $message;
    }

    public function getFormActionUrl()
    {
        return $this->getUrl('tellafriend/index/post', array('_secure' => true));
    }
}