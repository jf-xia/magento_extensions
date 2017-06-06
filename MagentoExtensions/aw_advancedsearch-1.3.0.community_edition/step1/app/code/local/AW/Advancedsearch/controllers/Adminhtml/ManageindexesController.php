<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedsearch
 * @version    1.3.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Advancedsearch_Adminhtml_ManageindexesController extends Mage_Adminhtml_Controller_Action
{
    protected function _setTitle($title)
    {
        if (Mage::helper('awadvancedsearch')->checkExtensionVersion('Mage_Core', '0.8.25')) {
            $this->_title($title);
        }
        return $this;
    }

    /**
     * Returns true when admin session contain error messages
     */
    protected function _hasErrors()
    {
        return (bool)count($this->_getSession()->getMessages()->getItemsByType('error'));
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('awadvancedsearch');
        return $this;
    }

    protected function indexAction()
    {
        $this->_setTitle($this->__('Advanced Search'))
            ->_setTitle($this->__('Indexes'));
        $this->_initAction()
            ->renderLayout();
    }

    protected function newAction()
    {
        return $this->_redirect('*/*/edit');
    }

    protected function editAction()
    {
        $this->_setTitle($this->__('Advanced Search'));
        if ($this->getRequest()->getParam('id')) {
            $this->_setTitle($this->__('Edit Index'));
        } else {
            $this->_setTitle($this->__('New Index'));
        }
        $this->_initAction();
        if (!$this->getRequest()->getParam('fswe') || !Mage::helper('awadvancedsearch/forms')->getFormData($this->getRequest()->getParam('id'))) {
            $_formData = Mage::getModel('awadvancedsearch/catalogindexes')->load($this->getRequest()->getParam('id'));
            if ($_formData->getData()) {
                Mage::helper('awadvancedsearch/forms')->setFormData($_formData);
            }
            if (!$_formData->getData() && $this->getRequest()->getParam('id')) {
                $this->_getSession()->addError($this->__('Couldn\'t load index by given ID'));
                return $this->_redirect('*/*/index');
            }
        }
        $this->renderLayout();
    }

    protected function typeformAction()
    {
        if (!$this->_validateFormKey()) {
            $this->getResponse()->setHeader('HTTP/1.1 403 Forbidden');
            return;
        }
        $result = array('s' => false);
        if (($typeId = $this->getRequest()->getParam('typeId'))) {
            $typeFieldset = null;
            switch ($typeId) {
                case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CATALOG:
                    $typeFieldset = 'awadvancedsearch/adminhtml_indexes_edit_fieldset_catalog';
                    break;
                case AW_Advancedsearch_Model_Source_Catalogindexes_Types::CMS_PAGES:
                    $typeFieldset = 'awadvancedsearch/adminhtml_indexes_edit_fieldset_cms_pages';
                    break;
                case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_BLOG:
                    $typeFieldset = 'awadvancedsearch/adminhtml_indexes_edit_fieldset_blog';
                    break;
                case AW_Advancedsearch_Model_Source_Catalogindexes_Types::AW_KBASE:
                    $typeFieldset = 'awadvancedsearch/adminhtml_indexes_edit_fieldset_kbase';
                    break;
            }
            if ($typeFieldset) {
                $result['fieldset'] = Mage::getSingleton('core/layout')->createBlock($typeFieldset)->toHtml();
                $result['s'] = true;
            } else {
                $result['fieldset'] = Mage::getSingleton('core/layout')->createBlock('awadvancedsearch/adminhtml_indexes_edit_fieldset_error')->toHtml();
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/x-json');
        $this->getResponse()->setBody(Zend_Json::encode($result));
        return;
    }

    protected function saveAction()
    {
        $data = array();
        $request = $this->getRequest();
        if ($request->getParam('status') !== null) {
            $data['status'] = $request->getParam('status');
            if ($request->getParam('type') && $request->getParam('type') !== null) {
                $data['type'] = $request->getParam('type');
                $data['store'] = is_array($request->getParam('store')) ? $request->getParam('store') : array(0);
                $collection = Mage::getModel('awadvancedsearch/catalogindexes')->getCollection();
                $collection->addTypeFilter($data['type'])
                    ->addStatusFilter()
                    ->addExceptIdFilter($request->getParam('id'));
                $allStores = array();
                foreach ($collection as $index) {
                    $allStores = array_merge($allStores, $index->getData('store'));
                }
                if (!in_array(0, $allStores) && !array_intersect($allStores, $data['store']) && (!$allStores || !in_array(0, $data['store']))) {
                    $data['attributes'] = is_array($request->getParam('attributes')) ? $request->getParam('attributes') : array();
                    $attributes = $uniqueAttributes = array();
                    foreach ($data['attributes'] as $attribute) {
                        if (!isset($attribute['delete']) || (isset($attribute['delete']) && !$attribute['delete'])) {
                            $attributes[] = $attribute;
                            if (!in_array($attribute['attribute'], $uniqueAttributes)) {
                                $uniqueAttributes[] = $attribute['attribute'];
                            }
                        }
                    }
                    $data['attributes'] = $attributes;
                    if (count($data['attributes'])) {
                        if (count($uniqueAttributes) == count($attributes)) {
                            unset($attributes);
                        } else {
                            $this->_getSession()->addError($this->__('Index should not contain same attributes'));
                        }
                    } else {
                        $this->_getSession()->addError($this->__('Index should contain at least that one attribute'));
                    }
                } else {
                    $this->_getSession()->addError($this->__('Index with the same type has been already created for at least that one of selected stores'));
                }
            } else {
                $this->_getSession()->addError($this->__('Type field is required'));
            }
        } else {
            $this->_getSession()->addError($this->__('Status field is required'));
        }
        $model = Mage::getModel('awadvancedsearch/catalogindexes')->load($request->getParam('id'));
        $data['state'] = $model->getData('state');
        $model->setData($data)
            ->setId($request->getParam('id'));
        if ($this->_hasErrors()) {
            Mage::helper('awadvancedsearch/forms')->setFormData($request->getParams());
            return $this->_redirect('*/*/edit', array('id' => $request->getParam('id'), 'fswe' => 1));
        } else {
            if (!$model->getData('status')) {
                $model->setData('state', AW_Advancedsearch_Model_Source_Catalogindexes_State::DISABLED);
            } else if ($model->getData('state') === null) {
                $model->setData('state', AW_Advancedsearch_Model_Source_Catalogindexes_State::NOT_INDEXED);
            } else {
                $model->setData('state', AW_Advancedsearch_Model_Source_Catalogindexes_State::REINDEX_REQUIRED);
            }
            $model->save();
            $this->_getSession()->addSuccess($this->__('Index has been succesfully saved'));
            if ($this->getRequest()->getParam('reindex')) {
                return $this->_redirect('*/*/reindex', array('id' => $model->getId()));
            }
        }
        return $this->_redirect('*/*/index');
    }

    protected function deleteAction()
    {
        $index = Mage::getModel('awadvancedsearch/catalogindexes')->load($this->getRequest()->getParam('id'));
        if ($index->getData()) {
            $indexer = $index->getIndexer();
            if ($indexer) {
                $indexer->removeTable();
                $sphinxIndexer = Mage::getModel('awadvancedsearch/engine_sphinx')->getInstance($indexer);
                if ($sphinxIndexer) {
                    $sphinxIndexer->removeVarDir();
                    $this->_getSession()->addSuccess($this->__('Index has been successfully deleted'));
                }
            }
            $index->delete();
        } else {
            $this->_getSession()->addError($this->__('Couldn\'t load index by given ID'));
        }
        return $this->_redirect('*/*/index');
    }

    protected function reindexAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('awadvancedsearch/catalogindexes')->load($id);
        if ($model->getData()) {
            $indexer = $model->getIndexer();
            if ($indexer) {
                $result = $indexer->reindex();
                if ($result === true) {
                    $sphinxIndexer = Mage::getModel('awadvancedsearch/engine_sphinx');
                    $result = $sphinxIndexer->reindex($indexer) && $sphinxIndexer->reindexDelta($indexer);
                    if ($result) {
                        if ($model->getState() != AW_Advancedsearch_Model_Source_Catalogindexes_State::DISABLED) {
                            $model->setData('state', AW_Advancedsearch_Model_Source_Catalogindexes_State::READY)
                                ->save();
                        }
                        $sphinxEngine = Mage::getModel('awadvancedsearch/engine_sphinx');
                        $sphinxEngine->restartSearchd();
                        $this->_getSession()->addSuccess($this->__('Index has been successfully rebuilt'));
                    } else {
                        $this->_getSession()->addError('Some error occurs on rebuilding index');
                    }
                } else if ($result === false) {
                    $this->_getSession()->addError($this->__('Some error occurs on rebuilding index'));
                } else {
                    $this->_getSession()->addError($result);
                }
            } else {
                $this->_getSession()->addError($this->__('Invalid indexer'));
            }
            if ($this->_hasErrors()) {
                $model->setData('state', AW_Advancedsearch_Model_Source_Catalogindexes_State::REINDEX_REQUIRED)
                    ->save();
            }
        } else {
            $this->_getSession()->addError($this->__('Couldn\'t load index by given ID'));
        }
        return $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        $helper = Mage::helper('awadvancedsearch');
        switch ($this->getRequest()->getActionName()) {
            case 'delete':
            case 'new':
            case 'reindex':
            case 'save':
                return $helper->isEditAllowed();
                break;
            case 'edit':
            case 'index':
            case 'typeform':
                return $helper->isViewAllowed() || $helper->isEditAllowed();
                break;
            default:
                return false;
        }
    }
}
