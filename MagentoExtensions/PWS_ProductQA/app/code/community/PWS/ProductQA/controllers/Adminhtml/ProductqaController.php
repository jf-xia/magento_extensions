<?php
class PWS_ProductQA_Adminhtml_ProductqaController extends Mage_Adminhtml_Controller_Action
{
    const XML_PATH_EMAIL_PRODUCT_QUESTION_IDENTITY  = 'default/pws_productqa/emails/email_identity';
	const XML_PATH_EMAIL_PRODUCT_QUESTION_TEMPLATE  = 'product_qa_answer';
		
    const CONFIG_SEND_USER_EMAIL = 'pws_productqa/general/send_email';
    
    
    public function indexAction()
    {                
        $this->loadLayout();

        $this->_setActiveMenu('catalog/pws_productqa');
        $this->_addBreadcrumb(Mage::helper('pws_productqa')->__('Manage Product Questions'), Mage::helper('pws_productqa')->__('Manage Product Questions'));
        $this->_addContent($this->getLayout()->createBlock('pws_productqa/adminhtml_productqa_list'));

        $this->renderLayout();
    }
    
    // mass action
    public function massDeleteAction()
    {
        $productqaIds = $this->getRequest()->getParam('productqa');
        if(!is_array($productqaIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select at least one question'));
        } else {
            try {
                $productqa = Mage::getModel('pws_productqa/productqa');
                foreach ($productqaIds as $productqaId) {
                    $productqa->load($productqaId)
                        ->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($productqaIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }


    public function newAction()
    {
        $this->_forward('edit');
    }
    
    public function editAction()
    {       
        $this->loadLayout();
        
        $recordId  = (int) $this->getRequest()->getParam('id');
        $recordModel   = Mage::getModel('pws_productqa/productqa');
        $record = $recordModel->loadExtra($recordId);
        
        if (!$record->getId()) {
            $message = $this->__('Invalid record id');
            Mage::getSingleton('adminhtml/session')->addError($message);
            $this->_redirect('*/*/');
            return;
        }        
        
        Mage::register('productqa', $record);
        Mage::register('current_productqa', $record);

        $this->_setActiveMenu('catalog/pws_productqa');
        $this->_addBreadcrumb(Mage::helper('pws_productqa')->__('Manage Product Question and Answers'), Mage::helper('pws_productqa')->__('Manage Product Question and Answers'));

        $this->_addContent($this->getLayout()->createBlock('pws_productqa/adminhtml_productqa_edit'));
        $this->_addLeft($this->getLayout()->createBlock('pws_productqa/adminhtml_productqa_edit_tabs'));
        $this->renderLayout();
    }

    

    public function saveAction()
    {

        if ($this->getRequest()->getPost()) {
            try {
                $recordModel = Mage::getModel('pws_productqa/productqa')->load($this->getRequest()->getParam('id'));
                $answeredOnDate = Mage::getModel('pws_productqa/productqa')->getResource()->formatDate(time());
              
                $record_data = $this->getRequest()->getPost('record');
                
                $recordModel->setAnswer($record_data['answer']);   
                $recordModel->setStatus($record_data['status']);
                $recordModel->setAnsweredOn($answeredOnDate);         
                $recordModel->save();
             
             
                // --------------------- SEND EMAIL TO POSTER
                
                $sendEmailToPoster = Mage::getStoreConfig(self::CONFIG_SEND_USER_EMAIL);
                
                if ($sendEmailToPoster) {                
                    $emailData = array();
                    $emailData['to_email'] = $record_data['email'];
                    $emailData['to_name'] = $record_data['name'];
                    $emailData['email'] = array(
                        'product_name' => $record_data['product_name'],
                        'store_id' => $recordModel->getStoreId(),
                        'store_name' => $record_data['store_name'],
                        'question' => $record_data['question'],
                        'answer' => $record_data['answer'],
                        'customer_name' => $record_data['name'],
                        'date_posted' => Mage::helper('core')->formatDate($recordModel->getCreatedOn(), 'long'), 
                    ); 
		            $result = $this->sendEmail($emailData);
		            
		            if(!$result) {
		                Mage::throwException($this->__('Cannot send email'));
		            }
		        }                               
         
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('pws_productqa')->__('The record has been successfully saved'));
                Mage::getSingleton('adminhtml/session')->setRecordData(false);

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setRecordData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $recordModel = Mage::getModel('pws_productqa/productqa');
                
                $recordModel->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('pws_productqa')->__('The record has been successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
   
    
    private function sendEmail($data)
	{	
		
		$storeID = $data['email']['store_id'];
		
		$translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $result = Mage::getModel('core/email_template')
            ->setDesignConfig(array('area' => 'frontend', 'store' => $storeID));
        
        $result->sendTransactional(
                self::XML_PATH_EMAIL_PRODUCT_QUESTION_TEMPLATE,
                Mage::getConfig()->getNode(self::XML_PATH_EMAIL_PRODUCT_QUESTION_IDENTITY),
                $data['to_email'],
                $data['to_name'],
                $data['email'],
                $storeID
               );
        //$result->getProcessedTemplate($data);       

        $translate->setTranslateInline(true);
        
        return $result;
	}
    
}    
