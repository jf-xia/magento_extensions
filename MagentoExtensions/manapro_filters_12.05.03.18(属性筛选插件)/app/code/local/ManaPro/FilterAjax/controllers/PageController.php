<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

require_once BP.DS.'app'.DS.'code'.DS.'core'.DS.'Mage'.DS.'Cms'.DS.'controllers'.DS.'PageController.php';
/**
 * AJAX version of standard category controller
 * @author Mana Team
 *
 */
class ManaPro_FilterAjax_PageController extends Mage_Cms_PageController {
    public function viewAction()
    {
        $pageId = $this->getRequest()
            ->getParam('page_id', $this->getRequest()->getParam('id', false));
        if (!Mage::helper('manapro_filterajax/page')->renderPage($this, $pageId)) {
            $this->_forward('noRoute');
        }
    }
}