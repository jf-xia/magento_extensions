<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Tellafriend
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Tellafriend_IndexController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_EMAIL_SENDER     = 'tellafriend/email/email_sender';
    const XML_PATH_EMAIL_TEMPLATE   = 'tellafriend/email/email_template';

    public function indexAction(){
	#$this->loadLayout();
        #$this->_initLayoutMessages('customer/session');
        #$this->_initLayoutMessages('catalog/session');
        #$this->renderLayout();
    }
   
    public function postAction()
    {
	$session            = Mage::getSingleton('core/session');
	$post		    = $this->getRequest()->getPost();
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);
                $postObject->setData('url', Mage::getUrl());

                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
		
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($replyTo)
                    ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        $post['email'],
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

                $session->addSuccess(Mage::helper('tellafriend')->__('Thank you for telling your friend about us.'));
                $this->_redirectReferer();

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                $session->addError(Mage::helper('tellafriend')->__('There was some error processing your request.'));
                $this->_redirectReferer();
                return;
            }

        } else {
            $this->_redirectReferer();
        }
    	
    }
}