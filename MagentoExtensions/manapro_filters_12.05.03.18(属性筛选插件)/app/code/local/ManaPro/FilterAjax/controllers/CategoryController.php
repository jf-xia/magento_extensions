<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

require_once BP.DS.'app'.DS.'code'.DS.'core'.DS.'Mage'.DS.'Catalog'.DS.'controllers'.DS.'CategoryController.php';
/**
 * AJAX version of standard category controller
 * @author Mana Team
 *
 */
class ManaPro_FilterAjax_CategoryController extends Mage_Catalog_CategoryController {
    public function viewAction()
    {
        if ($category = $this->_initCatagory()) {
            $design = Mage::getSingleton('catalog/design');
            if ($settings = $design->getDesignSettings($category)) { // 1.4.2 and later
	            // apply custom design
	            if ($settings->getCustomDesign()) {
	                $design->applyCustomDesign($settings->getCustomDesign());
	            }
            }
            else { // prior 1.4.2
            	$design->applyDesign($category, Mage_Catalog_Model_Design::APPLY_FOR_CATEGORY);
            }

            Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());

            $update = $this->getLayout()->getUpdate();
            $update->addHandle(strtolower($this->getFullActionName()));
            $this->getRequest()->setModuleName('catalog')->setRouteName('catalog');
            $this->loadLayoutUpdates();
            
            // apply custom layout update once layout is loaded
            if ($settings) { // 1.4.2 and later
	            if ($layoutUpdates = $settings->getLayoutUpdates()) {
	                if (is_array($layoutUpdates)) {
	                    foreach($layoutUpdates as $layoutUpdate) {
	                        $update->addUpdate($layoutUpdate);
	                    }
	                }
	            }
            }
            else { // prior 1.4.2
            	$update->addUpdate($category->getCustomLayoutUpdate());
            }
            $this->generateLayoutXml()->generateLayoutBlocks();
            Mage::dispatchEvent('controller_action_layout_render_before_'.$this->getFullActionName());
            $response = $this->getLayout()->getBlock('m_ajax_update')->toAjaxHtml();
        }
        elseif (!$this->getResponse()->isRedirect()) {
            $response = array('error' => $this->__('Invalid category.'));
        }
		$this->getResponse()->setBody(json_encode($response));
    }
}