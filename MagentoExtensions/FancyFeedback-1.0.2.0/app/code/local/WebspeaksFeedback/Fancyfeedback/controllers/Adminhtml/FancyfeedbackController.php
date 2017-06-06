<?php

class WebspeaksFeedback_Fancyfeedback_Adminhtml_FancyfeedbackController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('fancyfeedback/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('fancyfeedback/fancyfeedback')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('fancyfeedback_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('fancyfeedback/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('fancyfeedback/adminhtml_fancyfeedback_edit'))
				->_addLeft($this->getLayout()->createBlock('fancyfeedback/adminhtml_fancyfeedback_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('fancyfeedback')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			$model = Mage::getModel('fancyfeedback/fancyfeedback');		
			$model->setData($data)->setId($this->getRequest()->getParam('id'));
			try {
				/*if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	*/
				
				$model->save();
				
				$thisModel = Mage::getModel('fancyfeedback/fancyfeedback')->load($this->getRequest()->getParam('id'));
				if($thisModel->getName() != '' && $thisModel->getEmail() != '')
				{
					$this->sendMail( $thisModel->getEmail(), $thisModel->getName(), substr('Re: '.$thisModel->getComment(),0,100), $data['reply']);
				}

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fancyfeedback')->__('Reply was sent successfully.'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('fancyfeedback')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('fancyfeedback/fancyfeedback');
				 
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

    public function massDeleteAction() {
        $fancyfeedbackIds = $this->getRequest()->getParam('fancyfeedback');
        if(!is_array($fancyfeedbackIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($fancyfeedbackIds as $fancyfeedbackId) {
                    $fancyfeedback = Mage::getModel('fancyfeedback/fancyfeedback')->load($fancyfeedbackId);
                    $fancyfeedback->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($fancyfeedbackIds)
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
        $fancyfeedbackIds = $this->getRequest()->getParam('fancyfeedback');
        if(!is_array($fancyfeedbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($fancyfeedbackIds as $fancyfeedbackId) {
                    $fancyfeedback = Mage::getSingleton('fancyfeedback/fancyfeedback')
                        ->load($fancyfeedbackId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($fancyfeedbackIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'fancyfeedback.csv';
        $content    = $this->getLayout()->createBlock('fancyfeedback/adminhtml_fancyfeedback_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'fancyfeedback.xml';
        $content    = $this->getLayout()->createBlock('fancyfeedback/adminhtml_fancyfeedback_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

	public function sendMail($email, $name, $subject='', $body='')
    {
        ini_set('SMTP', Mage::getStoreConfig('system/smtp/host'));
        ini_set('smtp_port', Mage::getStoreConfig('system/smtp/port'));

		
		$sender_email = Mage::getStoreConfig('fancyfeedbackconfig/fancyfeedback_group/fancyfeedback_senderemail');
		$sender_name = Mage::getStoreConfig('fancyfeedbackconfig/fancyfeedback_group/fancyfeedback_sendername');

		$mail = new Zend_Mail(); //class for mail
		$mail->setBodyHtml($body); //for sending message containing html code
		$mail->setFrom($sender_email, $sender_name);
		$mail->addTo($email, $name);
		//$mail->addCc($cc, $ccname);    //can set cc
		//$mail->addBCc($bcc, $bccname);    //can set bcc
		$mail->setSubject($subject);
		try {
			  if($mail->send())
			  {
				// die("Mail sent successfully to $to");
			  }
			}
		catch(Exception $ex) {
				// echo 'error->'.$error_msg = $ex->getMessage();
				// die("Error sending mail to $to,$error_msg");
		}
        return true;
    } 
}