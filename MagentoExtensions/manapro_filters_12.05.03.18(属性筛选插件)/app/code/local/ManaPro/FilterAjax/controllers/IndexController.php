<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

require_once BP.DS.'app'.DS.'code'.DS.'core'.DS.'Mage'.DS.'Cms'.DS.'controllers'.DS.'IndexController.php';
/**
 * AJAX version of standard category controller
 * @author Mana Team
 *
 */
class ManaPro_FilterAjax_IndexController extends Mage_Cms_IndexController {
    public function indexAction($coreRoute = null)
    {
        $pageId = Mage::getStoreConfig(Mage_Cms_Helper_Page::XML_PATH_HOME_PAGE);
        if (!Mage::helper('manapro_filterajax/page')->renderPage($this, $pageId)) {
            $this->_forward('noRoute');
        }
    }
}