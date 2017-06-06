<?php

class Wyomind_Datafeedmanager_Adminhtml_OptionsController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {

        $this->loadLayout()
                ->_setActiveMenu('catalog/datafeedmanager')
                ->_addBreadcrumb($this->__('Data Feed Manager'), ('Data Feed Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }
    public function editAction() {
       
    
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('datafeedmanager/options')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('datafeedmanager_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('catalog/datafeedmanager')->_addBreadcrumb(Mage::helper('datafeedmanager')->__('Data Feed Manager'), ('Data Feed Manager'));
            $this->_addBreadcrumb(Mage::helper('datafeedmanager')->__('Data Feed Manager'), ('Data Feed Manager'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('datafeedmanager/adminhtml_options_edit'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('datafeedmanager')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }
     public function saveAction() {
      
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {

            // init model and set data
            $model = Mage::getModel('datafeedmanager/options');

            if ($this->getRequest()->getParam('option_id')) {
                $model->load($this->getRequest()->getParam('option_id'));
            }
            $model->setData($data);
            // try to save it
            try {

                // save the data
                $model->save();

                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('datafeedmanager')->__('The custom option has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('continue')) {
                    $this->getRequest()->setParam('id', $model->getOptionId());
                    $this->_forward('edit');
                    return;
                }

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {

                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }
    
    public function deleteAction() {
      
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('datafeedmanager/options');
                $model->setId($id);
                $model->load($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('datafeedmanager')->__('The custom option has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                 $this->_redirect('*/*/');
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('datafeedmanager')->__('Unable to find the custom option to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }


}

