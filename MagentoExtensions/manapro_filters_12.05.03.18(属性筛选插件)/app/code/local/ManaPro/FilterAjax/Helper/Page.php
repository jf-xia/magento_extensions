<?php
/** 
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/**
 * @author Mana Team
 *
 */
class ManaPro_FilterAjax_Helper_Page extends Mage_Cms_Helper_Page  {
    protected function _renderPage(Mage_Core_Controller_Varien_Action  $action, $pageId = null, $renderLayout = true)
    {
        $page = Mage::getSingleton('cms/page');
        if (!is_null($pageId) && $pageId!==$page->getId()) {
            $delimeterPosition = strrpos($pageId, '|');
            if ($delimeterPosition) {
                $pageId = substr($pageId, 0, $delimeterPosition);
            }

            $page->setStoreId(Mage::app()->getStore()->getId());
            if (!$page->load($pageId)) {
                return false;
            }
        }

        if (!$page->getId()) {
            return false;
        }

        $inRange = Mage::app()->getLocale()->isStoreDateInInterval(null, $page->getCustomThemeFrom(), $page->getCustomThemeTo());

        if ($page->getCustomTheme()) {
            if ($inRange) {
                list($package, $theme) = explode('/', $page->getCustomTheme());
                Mage::getSingleton('core/design_package')
                    ->setPackageName($package)
                    ->setTheme($theme);
            }
        }

        $action->getLayout()->getUpdate()
            ->addHandle('default')
            ->addHandle('cms_page');

        $action->addActionLayoutHandles();
        if ($page->getRootTemplate()) {
            $handle = ($page->getCustomRootTemplate()
                        && $page->getCustomRootTemplate() != 'empty'
                        && $inRange) ? $page->getCustomRootTemplate() : $page->getRootTemplate();
            $action->getLayout()->helper('page/layout')->applyHandle($handle);
        }

        Mage::dispatchEvent('cms_page_render', array('page' => $page, 'controller_action' => $action));

        $action->loadLayoutUpdates();
        $layoutUpdate = ($page->getCustomLayoutUpdateXml() && $inRange) ? $page->getCustomLayoutUpdateXml() : $page->getLayoutUpdateXml();
        $action->getLayout()->getUpdate()->addUpdate($layoutUpdate);
        $action->generateLayoutXml()->generateLayoutBlocks();

        $contentHeadingBlock = $action->getLayout()->getBlock('page_content_heading');
        if ($contentHeadingBlock) {
            $contentHeadingBlock->setContentHeading($page->getContentHeading());
        }

        if ($page->getRootTemplate()) {
            $action->getLayout()->helper('page/layout')
                ->applyTemplate($page->getRootTemplate());
        }

        foreach (array('catalog/session', 'checkout/session') as $class_name) {
            $storage = Mage::getSingleton($class_name);
            if ($storage) {
                $action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
            }
        }

        if ($renderLayout) {
            $action->getResponse()->setBody(json_encode($action->getLayout()->getBlock('m_ajax_update')->toAjaxHtml()));
        }

        return true;
    }
}