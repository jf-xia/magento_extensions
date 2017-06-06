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
class Soon_AdvancedCache_Adminhtml_AdvancedCache_BlocksController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('system/advanced_cache')
                ->_addBreadcrumb(Mage::helper('advancedcache')->__('Cache'), Mage::helper('advancedcache')->__('Cache'))
                ->_addBreadcrumb(Mage::helper('advancedcache')->__('Blocks'), Mage::helper('advancedcache')->__('Blocks'))
        ;

        return $this;
    }

    /**
     * Cache blocks list
     */
    public function indexAction() {
        
        $this->_title($this->__('Cache'))->_title($this->__('Blocks Cache'));

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
                $this->getLayout()->createBlock('advancedcache/adminhtml_blocks_grid')->toHtml()
        );
    }

    /**
     * Enabled and disable block caches
     */    
    public function massStatusAction() {
        $blocksIds = $this->getRequest()->getParam('blocks');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($blocksIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedcache')->__('Please select block(s)'));
        } else {
            try {
                foreach ($blocksIds as $blockId) {
                    $block = Mage::getModel('advancedcache/blocks')->load($blockId);
                    if ($block->getStatus() != $status) {
                        if ($status == 0) {
                            $block->disable();
                        }
                        if ($status == 1) {
                            $block->enable();
                        }
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were updated', count($blocksIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Refresh block caches
     */    
    public function massRefreshAction() {
        $blocksIds = $this->getRequest()->getParam('blocks');
        if (!is_array($blocksIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedcache')->__('Please select block(s)'));
        } else {
            try {
                foreach ($blocksIds as $blockId) {
                    $block = Mage::getModel('advancedcache/blocks')->load($blockId);
                    $block->cleanAdminBlockCache();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d cache(s) have been cleared.', count($blocksIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
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
        $this->_title($this->__('Cache'))->_title($this->__('Blocks Cache'));
        $id = $this->getRequest()->getParam('block_id');
        $model = Mage::getModel('advancedcache/blocks');

        if ($id) {
            $model->load($id);
            if (!$model->getBlockId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedcache')->__('This block no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getBlockId() ? $model->getIdentifier() : $this->__('New Cache Block'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('advancedcache_block', $model);

        $this->_initAction()
                ->_addBreadcrumb($id ? Mage::helper('advancedcache')->__('Edit Cache Block') : Mage::helper('advancedcache')->__('New Cache Block'), $id ? Mage::helper('advancedcache')->__('Edit Cache Block') : Mage::helper('advancedcache')->__('New Cache Block'))
                ->renderLayout();
    }

    /**
     * Save action
     */
    public function saveAction() {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {

            $id = $this->getRequest()->getParam('block_id');
            $model = Mage::getModel('advancedcache/blocks')->load($id);
            if (!$model->getBlockId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedcache')->__('This block no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            // init model and set data
            $model->setData($data);

            // check if 'Clean Cache'
            if ($this->getRequest()->getParam('clean')) {
                $this->_forward('cleanCache', null, null, array('model' => $model));
                return;
            }

            // try to save it
            try {
                // save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('advancedcache')->__('The block has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('block_id' => $model->getBlockId()));
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
                $this->_redirect('*/*/edit', array('block_id' => $this->getRequest()->getParam('block_id')));
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
        if ($id = $this->getRequest()->getParam('block_id')) {
            $title = "";
            try {
                // init model and delete
                $model = Mage::getModel('advancedcache/blocks');
                $model->load($id);
                $title = $model->getIdentifier();
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('advancedcache')->__('The block has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('block_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedcache')->__('Unable to find a block to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }

    /*
     * Clean cache action
     */

    public function cleanCacheAction() {
        $model = $this->getRequest()->getParam('model');
        $model->cleanAdminBlockCache();
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('advancedcache')->__('The cache has been cleared.'));
        $this->_redirect('*/*/edit', array('block_id' => $model->getBlockId()));
        return;
    }

}
