<?php
class PWS_ProductQA_IndexController extends Mage_Core_Controller_Front_Action
{
   
   const CONFIG_SEND_NOTIFICATION_EMAIL = 'pws_productqa/general/send_notification';
   const CONFIG_SEND_NOTIFICATION_EMAIL_TO = 'pws_productqa/general/notification_email';
   
   const XML_PATH_EMAIL_PRODUCT_QUESTION_IDENTITY  = 'default/pws_productqa/emails/email_identity';
   const XML_PATH_EMAIL_PRODUCT_NOTIFICATION_TEMPLATE  = 'product_qa_notification';
   
   public function addQuestionAction()
   {
        $post = $this->getRequest()->getPost();
        if ($post) {
           
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['question']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }
                
                $post['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $productqa = Mage::getModel('pws_productqa/productqa');
                $productqa->setData($post);
                $productqa->save();
                
                
                // --------------------- SEND NOTIFICATION EMAIL
                
                $sendNotificationEmail = Mage::getStoreConfig(self::CONFIG_SEND_NOTIFICATION_EMAIL);
               
                               
                if ($sendNotificationEmail) {  
                    $product = Mage::getModel('catalog/product')->load($productqa->getProductId());
                              
                    $emailData = array();
                    $emailData['to_email'] = Mage::getStoreConfig(self::CONFIG_SEND_NOTIFICATION_EMAIL_TO);
                    $emailData['to_name'] =  Mage::getConfig()->getNode(self::XML_PATH_EMAIL_PRODUCT_QUESTION_IDENTITY);
                    $emailData['email'] = array(
                        'product_name' => $product->getName(),
                        'store_id' => $productqa->getStoreId(),
                        'question' => $productqa->getQuestion(),
                        'date_posted' => Mage::helper('core')->formatDate($productqa->getCreatedOn(), 'long'), 
                    ); 
		            $result = $this->sendEmail($emailData);
		            
		            if(!$result) {
		                Mage::throwException($this->__('Cannot send email'));
		            }
		        }      
                

                Mage::getSingleton('catalog/session')->addSuccess(Mage::helper('pws_productqa')->__('Thank You! We\'ll reply to your question as soon as possible '));
                
                return $this->_redirectReferer();
            } catch (Exception $e) {               

                Mage::getSingleton('catalog/session')->addError(Mage::helper('pws_productqa')->__('Unable to submit your question. Please, try again later'.$e->getMessage()));
                
                return $this->_redirectReferer();
            }

        } else {
            return $this->_redirectReferer();
        }
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
                self::XML_PATH_EMAIL_PRODUCT_NOTIFICATION_TEMPLATE,
                Mage::getConfig()->getNode(self::XML_PATH_EMAIL_PRODUCT_QUESTION_IDENTITY),
                $data['to_email'],
                $data['to_name'],
                $data['email'],
                $storeID
               );
        //echo $result->getProcessedTemplate($data);       

        $translate->setTranslateInline(true);
        
        return $result;
	}
   
}
