<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Common
 * @version    0.6.11
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Common_Controller_Adminhtml_Action_Grid extends Magpleasure_Common_Controller_Adminhtml_Action
{
    protected $_modelName;
    protected $_aclAllowCheckPath;
    protected $_menuPath;
    protected $_messages = array();
    protected $_registryKey;
    protected $_sessionDataKey;
    protected $_idField = 'id';
    protected $_massActionField;
    protected $_massUpdateStatusValue = 'status';

    const KEY_SUCCESS = 'success';
    const KEY_ERROR = 'error';

    /**
     * Helper
     *
     * @return Magpleasure_Common_Helper_Data
     */
    protected function _helper()
    {
        return $this->_commonHelper();
    }

    protected function _getActionName()
    {
        return $this->getRequest()->getActionName();
    }

    protected function _addMessage($action, $key, $message)
    {
        if (!isset($this->_messages[$action])){
            $this->_messages[$action] = array();
        }

        if (!isset($this->_messages[$action][$key])){
            $this->_messages[$action][$key] = "";
        }

        $this->_messages[$action][$key] = $message;

        return $this;
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed($this->_aclAllowCheckPath);
    }

    /**
     * Initialize layout prefer any action
     * @return Magpleasure_Common_Controller_Adminhtml_Action_Grid
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu($this->_menuPath)
            ;

        return $this;
    }

    public function indexAction()
    {
        $this
            ->_initAction()
            ->renderLayout()
        ;
    }

    public function gridAction()
    {
        $this
            ->_initAction()
            ->renderLayout()
        ;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam($this->_idField);
        $item = Mage::getModel($this->_modelName);
        if ($id){
            $item->load($id);
        }

        if ($item->getId() || !$id){

            Mage::register($this->_registryKey, $item);
            $data = $this->_getSession()->getData($this->_sessionDataKey, true);

            if (!empty($data)) {
                $item->setData($data);
            }

            $this->_initAction();
            $this->renderLayout();

        } else {

            $this->_getSession()->addError($this->_messages[$this->_getActionName()][self::KEY_ERROR]);
            $this->_redirect('*/*/index');
        }
    }


    public function saveAction()
    {
        $itemData = $this->getRequest()->getPost();

        /** @var Magpleasure_Common_Model_Abstract $item  */
        $item = Mage::getModel($this->_modelName);
        if ($id = $this->getRequest()->getParam($this->_idField)){
            $item->load($id);
        }

        try {
            $item->addData($itemData);

            $item->save();
            $this->_getSession()->addSuccess($this->_messages[$this->_getActionName()][self::KEY_SUCCESS]);

            if ($this->getRequest()->getParam('back')){

                $params = array($this->_idField => $item->getId());
                if ($tab = $this->getRequest()->getParam('tab')){
                    $params['tab'] = $tab;
                }

                $this->_redirect('*/*/edit', $params);
            } else {
                $this->_redirect('*/*/index');
            }

        } catch (Exception $e) {
            $this->_getSession()->setData($this->_sessionDataKey, $itemData);
            $this->_getSession()->addError($this->_messages[$this->_getActionName()][self::KEY_ERROR]);
            $this->_commonHelper()->getException()->logException($e);
            $this->_redirectReferer();
        }

    }

    /**
     * Delete abstract item
     * @param int|string $id
     * @return boolean
     */
    protected function _delete($id)
    {
        $item = Mage::getModel($this->_modelName)->load($id);
        if ($item->getId()){
            try{
                $item->delete();
                return true;
            } catch(Exception $e) {
                $this->_commonHelper()->getException()->logException($e);
                return false;
            }
        }
        return false;
    }

    /**
     * Duplicate abstract item
     * @param int|string $id
     * @return boolean
     */
    protected function _duplicate($id)
    {
        $item = Mage::getModel($this->_modelName)->load($id);
        if ($item->getId()){
            try{
                $newItem = $item->duplicate();
                return $newItem;
            } catch(Exception $e) {
                $this->_commonHelper()->getException()->logException($e);
                return false;
            }
        }
        return false;
    }

    protected function _updateFields($id, array $bind)
    {
        if ($id){
            try {
                $post = Mage::getModel($this->_modelName)->load($id);
                $post->addData($bind);
                $post->save();
                return true;
            } catch (Exception $e){
                $this->_commonHelper()->getException()->logException($e);
                return false;
            }
        }
        return false;
    }

    protected function _updateField($id, $field, $value)
    {
        return $this->_updateFields($id, array($field => $value));
    }

    public function massStatusAction()
    {
        $itemIds = $this->getRequest()->getPost($this->_massActionField);
        $statusValue = $this->getRequest()->getPost($this->_massUpdateStatusValue);
        if ($itemIds){
            $success = 0;
            $error = 0;
            foreach ($itemIds as $itemId){
                if ($this->_updateField($itemId, 'status', $statusValue)){
                    $success++;
                } else {
                    $error++;
                }
            }
            if ($success){
                $this->_getSession()->addSuccess(sprintf($this->_messages[$this->_getActionName()][self::KEY_SUCCESS], $success));
            }
            if ($error){
                $this->_getSession()->addError(sprintf($this->_messages[$this->_getActionName()][self::KEY_ERROR], $error));
            }
        }

        $this->_redirectReferer();
    }

    public function massDeleteAction()
    {
        $itemIds = $this->getRequest()->getPost($this->_massActionField);
        if ($itemIds){
            $success = 0;
            $error = 0;
            foreach ($itemIds as $itemId){
                if ($this->_delete($itemId)){
                    $success++;
                } else {
                    $error++;
                }
            }
            if ($success){
                $this->_getSession()->addSuccess(sprintf($this->_messages[$this->_getActionName()][self::KEY_SUCCESS], $success));
            }
            if ($error){
                $this->_getSession()->addError(sprintf($this->_messages[$this->_getActionName()][self::KEY_ERROR], $error));
            }
        }
        $this->_redirectReferer();
    }

    public function massDuplicateAction()
    {
        $itemIds = $this->getRequest()->getPost($this->_massActionField);
        if ($itemIds){
            $success = 0;
            $error = 0;
            foreach ($itemIds as $itemId){
                if ($this->_duplicate($itemId)){
                    $success++;
                } else {
                    $error++;
                }
            }
            if ($success){
                $this->_getSession()->addSuccess(sprintf($this->_messages[$this->_getActionName()][self::KEY_SUCCESS], $success));
            }
            if ($error){
                $this->_getSession()->addError(sprintf($this->_messages[$this->_getActionName()][self::KEY_ERROR], $error));
            }
        }
        $this->_redirectReferer();
    }


    public function duplicateAction()
    {
        $id = $this->getRequest()->getParam($this->_idField);
        if ($id){
            if ($newItem = $this->_duplicate($id)) {
                $this->_getSession()->addSuccess($this->_messages[$this->_getActionName()][self::KEY_SUCCESS]);
                $this->_redirect('*/*/edit', array($this->_idField => $newItem->getId()));

            } else {

                $this->_getSession()->addError($this->_messages[$this->_getActionName()][self::KEY_ERROR]);
                $this->_redirectReferer();
                return;
            }
        } else {

            $this->_getSession()->addError($this->_messages[$this->_getActionName()][self::KEY_ERROR]);
            $this->_redirectReferer();
            return;
        }
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam($this->_idField);
        if ($id){
            if ($this->_delete($id)) {
                $this->_getSession()->addSuccess($this->_messages[$this->_getActionName()][self::KEY_SUCCESS]);
            } else {
                $this->_getSession()->addError($this->_messages[$this->_getActionName()][self::KEY_ERROR]);
                $this->_redirectReferer();
                return;
            }
        } else {

            $this->_getSession()->addError($this->_messages[$this->_getActionName()][self::KEY_ERROR]);
            $this->_redirectReferer();
            return;
        }

        $this->_redirect('*/*/index');
    }

}