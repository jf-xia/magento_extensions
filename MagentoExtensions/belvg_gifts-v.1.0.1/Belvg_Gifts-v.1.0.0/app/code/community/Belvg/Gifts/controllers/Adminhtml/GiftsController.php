<?php

/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
 /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 *****************************************/
 /* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
 /***************************************
 *         DISCLAIMER   *
 *****************************************/
 /* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 *****************************************************
 * @category   Belvg
 * @package    Belvg_Gifts
 * @copyright  Copyright (c) 2010 - 2012 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_Gifts_Adminhtml_GiftsController extends Mage_Adminhtml_Controller_action {

	protected function _initAction()
    {
        $this->loadLayout()
                ->_setActiveMenu('gifts/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Rules Manager'), Mage::helper('adminhtml')->__('Rules Manager'));
        return $this;
    }
    
    public function indexAction()
    {
        $this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('adminhtml/store_switcher', 'store_switcher'));
        $this->_addContent($this->getLayout()->createBlock('gifts/adminhtml_gifts'));
        $this->getLayout()->getBlock('head')->setTitle($this->__('Gift Rules List'));
        $this->renderLayout();
    }
    
    public function newAction()
    {
        $this->_forward('edit');
    }
    
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('gifts/gifts')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('gifts_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('gifts/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->getLayout()->getBlock('head')->setTitle($this->__('Edit Gift Rule'));

            $this->_addContent($this->getLayout()->createBlock('gifts/adminhtml_gifts_edit'))
                    ->_addLeft($this->getLayout()->createBlock('gifts/adminhtml_gifts_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gifts')->__('Rule does not exist'));
            $this->_redirect('*/*/');
        }
    }
    
    
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('gifts/gifts');

            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }
                
				$model->setStatus($this->getRequest()->getParam('gift_status'));
				$model->setStore($this->getRequest()->getParam('store_switcher'));
                $model->save();
                
                $gift_id = $model->getId();
                
                $products = $this->getRequest()->getParam('gifts_assigned_products');
                $products = explode('&',$products);
                foreach ($products as $key => $product) {
                    if (!is_numeric($product)) {
                        unset($products[$key]);
                    }
                }
                
                if(!count($products))Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gifts')->__('Gifts weren\'t selected'));
                if ($gift_id && count($products)) {
                    $products_model = Mage::getModel('gifts/product')->getCollection()->addFieldToFilter('gift_id', $gift_id)->load();
                    foreach ($products_model as $product) {
                        Mage::getModel('gifts/product')->load($product->getId())->delete();
                    }

                    foreach ($products as $product) {
                        $data = array('gift_id'=>$gift_id, 'product_id'=>$product);
                        $m = Mage::getModel('gifts/product');
                        $m->setGiftId($gift_id);
                        $m->setProductId($product);
                        $m->save();
                    }
                }
                
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('gifts')->__('Rule was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), 'store' => $this->getRequest()->getParam('store_switcher')));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'store' => $this->getRequest()->getParam('store_switcher')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('sheruns')->__('Unable to find rule to save'));
        $this->_redirect('*/*/');
    }
    
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('gifts/gifts');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
    
    public function massDeleteAction()
    {
        $giftsIds = $this->getRequest()->getParam('gifts');
        if (!is_array($giftsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($giftsIds as $giftId) {
                    $gifts = Mage::getModel('gifts/gifts')->load($giftId);
                    $gifts->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($giftsIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $giftsIds = $this->getRequest()->getParam('gifts');
        if (!is_array($giftsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($giftsIds as $giftId) {
                    $gifts = Mage::getSingleton('gifts/gifts')
                            ->load($giftId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($giftsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    public function exportCsvAction()
    {
        $fileName = 'gifts.csv';
        $content = $this->getLayout()->createBlock('gifts/adminhtml_gifts_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'gifts.xml';
        $content = $this->getLayout()->createBlock('gifts/adminhtml_gifts_grid')
                ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
    
    public function gridAction()
    {
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('gifts/adminhtml_gifts_edit_tab_products')->toHtml()
        );
    }
}