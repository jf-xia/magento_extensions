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
 * @package    Magpleasure_Guestbook
 * @version    1.1
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Guestbook_Adminhtml_MessageController extends Magpleasure_Common_Controller_Adminhtml_Action
{
    /**
     * Helper
     * @return Magpleasure_Guestbook_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('guestbook');
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/guestbook')
            ->_addBreadcrumb($this->_helper()->__('Guestbook'), $this->_helper()->__('Guestbook'));
        return $this;
    }


    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }


    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $comment = Mage::getModel('guestbook/message');
        if ($id){
            $comment->load($id);
        }

        $this->loadLayout();
        $this->_setActiveMenu('catalog/guestbook/comment');

        if ($comment->getId()){

            Mage::register('current_comment', $comment);
            $data = Mage::getSingleton('adminhtml/session')->getPostData(true);
            if (!empty($data)) {
                $comment->setData($data);
            }

            $this->_addBreadcrumb($this->_helper()->__('Guest Book'), $this->_helper()->__('Guest Book'));
            $this->_addBreadcrumb($this->_helper()->__('Comment'), $this->_helper()->__('Comment'));

        } else {
            $this->_getSession()->addError($this->_helper()->__('Comment is not exists.'));
            $this->_redirect('*/*/index');
        }

        $this->renderLayout();
    }


    public function saveAction()
    {
        $requestPost = $this->getRequest()->getPost();
        $comment = Mage::getModel('guestbook/message');
        if ($id = $this->getRequest()->getParam('id')){
            $comment->load($id);
        }

        try {
            $comment->addData($requestPost);
            $comment->save();
            $this->_getSession()->addSuccess($this->_helper()->__("Comment was successfully saved."));

            if ($this->getRequest()->getParam('back')){
                $this->_redirect('*/*/edit', array('id'=> $this->getRequest()->getParam('id') ? $this->getRequest()->getParam('id') : $comment->getId() ));
            } else {
                $this->_redirect('*/*/index');
            }

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->setPostData($requestPost);
            $this->_getSession()->addError($this->_helper()->__("Error while saving the comment (%s).", $e->getMessage()));
            $this->_redirectReferer();
        }

    }

    /**
     * Delete comment
     * @param int|string $id
     * @return boolean
     */
    protected function _delete($id)
    {
        $comment = Mage::getModel('guestbook/message')->load($id);
        if ($comment->getId()){
            try{
                $comment->delete();
                return true;
            } catch(Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Approve comment
     * @param int|string $id
     * @return boolean
     */
    protected function _approve($id)
    {
        $comment = Mage::getModel('guestbook/message')->load($id);
        if ($comment->getId()){
            try{
                $comment->approve();
                return true;
            } catch(Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Reject comment
     * @param int|string $id
     * @return boolean
     */
    protected function _reject($id)
    {
        $comment = Mage::getModel('guestbook/message')->load($id);
        if ($comment->getId()){
            try{
                $comment->reject();
                return true;
            } catch(Exception $e) {
                return false;
            }
        }
        return false;
    }

    protected function _updateStatus($id, $status)
    {
        if ($id){
            try {
                $comment = Mage::getModel('guestbook/message')->load($id);
                $comment->setStatus($status);
                $comment->save();
                return true;
            } catch (Exception $e){
                return false;
            }
        }
    }

    public function massStatusAction()
    {
        $comments = $this->getRequest()->getPost('comments');
        $status = $this->getRequest()->getPost('status');
        if ($comments){
            $success = 0;
            $error = 0;
            foreach ($comments as $commentId){
                if ($this->_updateStatus($commentId, $status)){
                    $success++;
                } else {
                    $error++;
                }
            }
            if ($success){
                $this->_getSession()->addSuccess($this->_helper()->__("%s comments successfully updated.", $success));
            }
            if ($error){
                $this->_getSession()->addError($this->_helper()->__("%s comments was not updated.", $error));
            }
        }
        $this->_redirectReferer();
    }

    public function massDeleteAction()
    {
        $comments = $this->getRequest()->getPost('comments');
        if ($comments){
            $success = 0;
            $error = 0;
            foreach ($comments as $commentId){
                if ($this->_delete($commentId)){
                    $success++;
                } else {
                    $error++;
                }
            }
            if ($success){
                $this->_getSession()->addSuccess($this->_helper()->__("%s comments successfully deleted.", $success));
            }
            if ($error){
                $this->_getSession()->addError($this->_helper()->__("%s comments was not deleted.", $error));
            }
        }
        $this->_redirectReferer();
    }

    public function approveAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id){
            try {
                $this->_approve($id);
                $this->_getSession()->addSuccess($this->_helper()->__("Comment was successfully approved."));
            } catch (Exception $e){
                $this->_getSession()->addError($this->_helper()->__("Comment was not approved (%s).", $e->getMessage()));
                $this->_redirectReferer();
                return;
            }
        }
        $this->_redirect('*/*/index');
    }

    public function rejectAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id){
            try {
                $this->_reject($id);
                $this->_getSession()->addSuccess($this->_helper()->__("Comment was successfully rejected."));
            } catch (Exception $e){
                $this->_getSession()->addError($this->_helper()->__("Comment was not rejected (%s).", $e->getMessage()));
                $this->_redirectReferer();
                return;
            }
        }
        $this->_redirect('*/*/index');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id){
            try {
                $this->_delete($id);
                $this->_getSession()->addSuccess($this->_helper()->__("Comment was successfully deleted."));
            } catch (Exception $e){
                $this->_getSession()->addError($this->_helper()->__("Comment was not deleted (%s).", $e->getMessage()));
                $this->_redirectReferer();
                return;
            }
        }
        $this->_redirect('*/*/index');
    }


    public function gridAction()
    {
        $grid = $this->getLayout()->createBlock('guestbook/adminhtml_message_grid');
        if ($grid){
            $this->getResponse()->setBody($grid->toHtml());
        }
    }

}
