<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G. 
 */
class Soon_AdvancedCache_Adminhtml_AdvancedCache_ExpireController extends Mage_Adminhtml_Controller_Action {

    /**
     * Save all cache expiration lead times
     */
    public function saveCacheExpireAction() {
        $config = Mage::getModel('advancedcache/config');
        $params = $this->getRequest()->getParams();
        $tags = $params['tags'];
        foreach ($tags as $tag => $value) {
            if (!is_numeric($value)) {
                $tagString = Mage::helper('advancedcache')->__($tag);
                Mage::getSingleton('adminhtml/session') ->addError(Mage::helper('advancedcache')->__('The value of %s cache is not valid. Please use numbers.', $tagString));
            }
            else {
                $config->setExpire($tag, $value);
            }
        }
        $this->_redirect('*/cache/index');
    }

}
