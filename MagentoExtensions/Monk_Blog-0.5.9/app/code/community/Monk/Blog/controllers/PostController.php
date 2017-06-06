<?php

require_once 'lib/recaptcha/recaptchalib.php';

class Monk_Blog_PostController extends Mage_Core_Controller_Front_Action
{
	public function preDispatch()
    {
        parent::preDispatch();
    }
	
    public function viewAction()
    {			
		$identifier = $this->getRequest()->getParam('identifier', $this->getRequest()->getParam('id', false));
		
		if ($data = $this->getRequest()->getPost()) {
			$model = Mage::getModel('blog/comment');		
			$model->setData($data);
			
			if (!Mage::getStoreConfig('blog/comments/enabled'))
			{
				Mage::getSingleton('customer/session')->addError(Mage::helper('blog')->__('Comments are not enabled.'));
				if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
					$this->_forward('NoRoute');
				}
                return;
			}

			if (!Mage::getSingleton('customer/session')->isLoggedIn() && Mage::getStoreConfig('blog/comments/login'))
			{
				Mage::getSingleton('customer/session')->addError(Mage::helper('blog')->__('You must be logged in to comment.'));
				if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
					$this->_forward('NoRoute');
				}
                return;
			}
			else if (Mage::getSingleton('customer/session')->isLoggedIn() && Mage::getStoreConfig('blog/comments/login'))
			{
				$model->setUser(Mage::helper('blog')->getUserName());
				$model->setEmail(Mage::helper('blog')->getUserEmail());
			}
			
			try 
			{
				
				if (Mage::getStoreConfig('blog/recaptcha/enabled'))
				{
					$publickey = Mage::getStoreConfig('blog/recaptcha/publickey');
					$privatekey = Mage::getStoreConfig('blog/recaptcha/privatekey');
					
					$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $data["recaptcha_challenge_field"], $data["recaptcha_response_field"]);
				
					if (!$resp->is_valid) 
					{
						if ($resp->error == "incorrect-captcha-sol")
						{
							Mage::getSingleton('customer/session')->addError(Mage::helper('blog')->__('Your Recaptcha soultion was incorrect, please try again'));	
						}
						else
						{
							Mage::getSingleton('customer/session')->addError(Mage::helper('blog')->__('An error occured. Please try again'));	
						}
						if (!Mage::helper('blog/comment')->renderPage($this, $identifier, $data)) {
							$this->_forward('NoRoute');
						}
						return;
					}
				}
				
				$model->setCreatedTime(now());
				$model->setComment(htmlspecialchars($model->getComment(), ENT_QUOTES));
				if (Mage::getStoreConfig('blog/comments/approval'))
				{
					$model->setStatus(2);
					Mage::getSingleton('customer/session')->addSuccess(Mage::helper('blog')->__('Your comment has been submitted.'));
				}
				else if (Mage::getSingleton('customer/session')->isLoggedIn() && Mage::getStoreConfig('blog/comments/loginauto'))
				{
					$model->setStatus(2);
					Mage::getSingleton('customer/session')->addSuccess(Mage::helper('blog')->__('Your comment has been submitted.'));
				}
				else
				{
					$model->setStatus(1);
					Mage::getSingleton('customer/session')->addSuccess(Mage::helper('blog')->__('Your comment has been submitted and is awaiting approval.'));
				}
				$model->save();
				
				$comment_id = $model->getCommentId();
                
            } catch (Exception $e) {
				if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
					$this->_forward('NoRoute');
				}
            }
			
			if (Mage::getStoreConfig('blog/comments/recipient_email') != null && $model->getStatus() == 1 && isset($comment_id))
			{
				$translate = Mage::getSingleton('core/translate');
				/* @var $translate Mage_Core_Model_Translate */
				$translate->setTranslateInline(false);
				try {
					$data["url"] = Mage::getUrl('blog/manage_comment/edit/id/' . $comment_id);
					$postObject = new Varien_Object();
					$postObject->setData($data);
					$mailTemplate = Mage::getModel('core/email_template');
					/* @var $mailTemplate Mage_Core_Model_Email_Template */
					$mailTemplate->setDesignConfig(array('area' => 'frontend'))
						->sendTransactional(
							Mage::getStoreConfig('blog/comments/email_template'),
							Mage::getStoreConfig('blog/comments/sender_email_identity'),
							Mage::getStoreConfig('blog/comments/recipient_email'),
							null,
							array('data' => $postObject)
						);
					$translate->setTranslateInline(true);
				} catch (Exception $e) {
					$translate->setTranslateInline(true);
				}
			}
			if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
				$this->_forward('NoRoute');
			}
		}
		else
		{
			if (!Mage::helper('blog/post')->renderPage($this, $identifier)) {
				$this->_forward('NoRoute');
			}
		}
    }
	
	public function noRouteAction($coreRoute = null)
    {
        $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
        $this->getResponse()->setHeader('Status','404 File not found');

        $pageId = Mage::getStoreConfig('web/default/cms_no_route');
        if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
            $this->_forward('defaultNoRoute');
        }
    }
	
	
}