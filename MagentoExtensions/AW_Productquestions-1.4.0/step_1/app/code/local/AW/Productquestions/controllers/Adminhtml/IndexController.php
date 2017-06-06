<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
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
 * @package    AW_Productquestions
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */
class AW_Productquestions_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed(){
        
        return Mage::getSingleton('admin/session')->isAllowed('admin/catalog/productquestions');
    }

    protected function _initAction() { return $this->loadLayout()->_setActiveMenu('catalog/items'); }

    public function indexAction() {

        $filter = $this->getRequest()->getParam('filter');
        $session = Mage::getSingleton('adminhtml/session');
        if(is_string($filter) || count($this->getRequest()->getParams()) == 1)
            $session->setFilter($filter);

        $this->_initAction()->renderLayout();
        }

    public function replyAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('productquestions/productquestions')->load($id);
        $session = Mage::getSingleton('adminhtml/session');

        if($model->getId() || $id == 0)
        {
            $data = $model->getData();

            $sessionData = $session->getProductquestionsData(true);
            if(!empty($sessionData)) $data = array_merge($data, $sessionData);
            $session->setProductquestionsData(false);

            Mage::register('productquestions_data', $data);

            $this->_initAction();

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('productquestions/adminhtml_productquestions_reply'))
                ->_addLeft($this->getLayout()->createBlock('productquestions/adminhtml_productquestions_reply_tabs'));

            $this->renderLayout();
        }
        else {
            $session->addError($this->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        $session = Mage::getSingleton('adminhtml/session');

        if($dataRequested = $this->getRequest()->getPost())
        {
            $data = $dataRequested;
            $id = $this->getRequest()->getParam('id');

            try
            {
                $data['question_store_ids'] = implode(',', $data['question_store_ids']);

                $locale = Mage::app()->getLocale();

                $model = Mage::getModel('productquestions/productquestions')
                        ->setData($data)
                        ->setQuestionDate($locale->date(
                                $data['question_date'],
                                $locale->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT), null, false
                            )
                            ->addTime(substr($data['question_datetime'], 10))
                            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT)
                        )
                        ->setId($id)
                        ->save();

                if($this->getRequest()->getParam('sendEmail'))
                {
                    $model->setQuestionReplyText(AW_Productquestions_Helper_Data::parseURLsIntoLinks($model->getQuestionReplyText()));

                    $storeId = $model->getQuestionStoreId();
                    $store = Mage::app()->getStore($storeId);
                    $product = Mage::getModel('catalog/product')->setStoreId($storeId)->load($model->getQuestionProductId());

                    $mailTemplate = Mage::getModel('core/email_template');
                    try
                    {
                        $sender = Mage::helper('productquestions')->getSender($storeId);
                        $mailTemplate->setReplyTo($sender['name'].' <'.$sender['mail'].'>');
                        $mailTemplate->setFromEmail($sender['mail']);
                    }
                    catch (Exception $ex)
                    { }
                    if(Mage::getStoreConfig(AW_Productquestions_Model_Source_Config_Path::EMAIL_SENDER, $storeId)==''){
                        $senderMail = array(
                            'name'  => Mage::getStoreConfig('trans_email/ident_general/name'),
                            'email' => Mage::getStoreConfig('trans_email/ident_general/email')
                        );
                    }
                    else
                        $senderMail = Mage::getStoreConfig(AW_Productquestions_Model_Source_Config_Path::EMAIL_SENDER, $storeId);
                    $mailTemplate
                        ->setDesignConfig(array('area' => 'frontend', 'store' => $store))
                        ->sendTransactional(
                            Mage::getStoreConfig(AW_Productquestions_Model_Source_Config_Path::EMAIL_CUSTOMER_TEMPLATE, $storeId),
                            $senderMail,
                            $model->getQuestionAuthorEmail(),
                            null,
                            array(
                                'data'          => $model,
                                'question_text' => $model->getQuestionText(),
                                'reply_text'    => $model->getQuestionReplyText(),
                                'customer_name' => $model->getQuestionAuthorName(),
                                'customer_email'=> $model->getQuestionAuthorEmail(),
                                'date_asked'    => Mage::app()->getLocale()->date($model->getQuestionDate(),
                                    Varien_Date::DATETIME_INTERNAL_FORMAT)->toString(
                                        Mage::app()->getLocale()->getDateTimeFormat(
                                            Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM)),
                                'product'       => $product,
                                'product_name'  => $product->getName(),
                                'product_url'   => $product->getProductUrl(),
                                'store'         => $store,
                                'store_url'     => $store->getUrl(),
                                'customer'      => Mage::getModel('customer/customer')
                                                    ->setWebsiteId($store->getWebsiteId())
                                                    ->loadByEmail($model->getQuestionAuthorEmail()),
                            ),
                            $storeId
                        );

                    if (!$mailTemplate->getSentSuccess())
                        throw new Exception('Message was successfully saved, but the email was not sent');
                    else
                        $session->addSuccess($this->__('Email was sent successfully'));
                }

                $session->addSuccess($this->__('Question was successfully saved'));
                $session->setProductquestionsData(false);

                if($this->getRequest()->getParam('back', false)) $this->_redirectReferer();
                else $this->_redirect('*/*/');

                return;
            }
            catch (Exception $e)
            {
                $session->addError($e->getMessage());
                Mage::logException($e);
                $session->setProductquestionsData($dataRequested);
                $this->_redirectReferer();
                return;
            }
        }
        $session->addError($this->__('Unable to find a data to save'));
        $this->_redirect('*/*/');
    }
 
    public function deleteAction()
    {
        if($id = $this->getRequest()->getParam('id'))
            try
            {
                Mage::getModel('productquestions/productquestions')
                    ->setId($id)
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Question was successfully deleted'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::logException($e);
            }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $productquestionsIds = $this->getRequest()->getParam('productquestions');
        if(!is_array($productquestionsIds))
        {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select question(s)'));
        }
        else
        {
            try {
                foreach ($productquestionsIds as $productquestionsId) {
                    $productquestions = Mage::getModel('productquestions/productquestions')->load($productquestionsId);
                    $productquestions->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('Total of %d question(s) were successfully deleted', count($productquestionsIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::logException($e);
            }
        }
        $this->_redirectReferer();
    }

    public function massStatusAction()
    {
        $productquestionsIds = $this->getRequest()->getParam('productquestions');
        if(!is_array($productquestionsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($productquestionsIds as $productquestionsId) {
                    $productquestions = Mage::getSingleton('productquestions/productquestions')
                        ->load($productquestionsId)
                        ->setQuestionStatus($this->getRequest()->getParam('question_status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($productquestionsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                Mage::logException($e);
            }
        }
        $this->_redirectReferer();
    }

    public function exportCsvAction()
    {
        $fileName = 'productquestions.csv';
        $content  = $this->getLayout()->createBlock('productquestions/adminhtml_productquestions_grid')
                    ->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'productquestions.xml';
        $content  = $this->getLayout()->createBlock('productquestions/adminhtml_productquestions_grid')
                    ->getXml();
        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}
