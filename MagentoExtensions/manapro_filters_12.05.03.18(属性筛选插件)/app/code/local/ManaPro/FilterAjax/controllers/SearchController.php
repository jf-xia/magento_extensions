<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

require_once BP.DS.'app'.DS.'code'.DS.'core'.DS.'Mage'.DS.'CatalogSearch'.DS.'controllers'.DS.'ResultController.php';
/**
 * AJAX version of standard search result controller
 * @author Mana Team
 *
 */
class ManaPro_FilterAjax_SearchController extends Mage_CatalogSearch_ResultController {
    public function indexAction()
    {
        $query = Mage::helper('catalogsearch')->getQuery();
        /* @var $query Mage_CatalogSearch_Model_Query */

        $query->setStoreId(Mage::app()->getStore()->getId());

        if ($query->getQueryText()) {
            if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            }
            else {
                if ($query->getId()) {
                    $query->setPopularity($query->getPopularity()+1);
                }
                else {
                    $query->setPopularity(1);
                }

                if ($query->getRedirect()){
                    $query->save();
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                }
                else {
                    $query->prepare();
                }
            }

            Mage::helper('catalogsearch')->checkNotes();

            $update = $this->getLayout()->getUpdate();
            $update->addHandle(strtolower($this->getFullActionName()));

            $this->getRequest()->setModuleName('catalogsearch')->setControllerName('result')->setRouteName('catalogsearch');
            
            $this->loadLayoutUpdates()->generateLayoutXml()->generateLayoutBlocks();
            Mage::dispatchEvent('controller_action_layout_render_before_'.$this->getFullActionName());
            $response = $this->getLayout()->getBlock('m_ajax_update')->toAjaxHtml();
            $this->getResponse()->setBody(json_encode($response));

            if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->save();
            }
        }
        else {
            $this->_redirectReferer();
        }
    }
}