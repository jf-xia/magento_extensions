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
class Soon_AdvancedCache_Adminhtml_AdvancedCache_ExceptionController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('system/advanced_cache')
                ->_addBreadcrumb(Mage::helper('advancedcache')->__('Cache'), Mage::helper('advancedcache')->__('Cache'))
                ->_addBreadcrumb(Mage::helper('advancedcache')->__('Exceptions'), Mage::helper('advancedcache')->__('Exceptions'))
        ;

        return $this;
    }

    /**
     * Cache blocks list
     */
    public function indexAction() {
        $this->_title($this->__('Cache'))->_title($this->__('Exceptions'));

        if ($this->getRequest()->getParam('ajax')) {
            $this->_forward('grid');
            return;
        }

        $this->_initAction();
        $this->renderLayout();
    }

    /*
     * Grid action for Ajax
     */

    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('advancedcache/adminhtml_exception_grid')->toHtml()
        );
    }

    /**
     * Create new Cache block
     */
    public function newAction() {
        // the same form is used to create and edit
        $this->_forward('edit');
    }

    /**
     * Edit Cache block
     */
    public function editAction() {
        $this->_title($this->__('Cache'))->_title($this->__('Exceptions'));
        $id = $this->getRequest()->getParam('exception_id');
        $model = Mage::getModel('advancedcache/exception');

        if ($id) {
            $model->load($id);
            if (!$model->getExceptionId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedcache')->__('This item no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getExceptionId() ? $model->getTitle() : $this->__('New Exception'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('advancedcache_exception', $model);

        $this->_initAction()
                ->_addBreadcrumb($id ? Mage::helper('advancedcache')->__('Edit Exception') : Mage::helper('advancedcache')->__('New Exception'), $id ? Mage::helper('advancedcache')->__('Edit Exception') : Mage::helper('advancedcache')->__('New Exception'))
                ->renderLayout();
    }

    /**
     * Save action
     */
    public function saveAction() {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {

            $id = $this->getRequest()->getParam('exception_id');
            $model = Mage::getModel('advancedcache/exception')->load($id);
            if (!$model->getExceptionId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedcache')->__('This item no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            // CAUTION : Waiting for future evolutions, we set the 'item_type'
            $data['item_type'] = 'cms_page';

            // init model and set data
            $model->setData($data);

            // try to save it
            try {
                // save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('advancedcache')->__('The exception has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('exception_id' => $model->getExceptionId()));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('exception_id' => $this->getRequest()->getParam('exception_id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Delete action
     */
    public function deleteAction() {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('exception_id')) {
            try {
                // init model and delete
                $model = Mage::getModel('advancedcache/exception');
                $model->load($id);
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('advancedcache')->__('The exception has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('exception_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedcache')->__('Unable to find an exception to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }

}
