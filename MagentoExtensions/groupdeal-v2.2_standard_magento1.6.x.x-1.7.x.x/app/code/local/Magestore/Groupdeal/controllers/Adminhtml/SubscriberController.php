<?php

class Magestore_Groupdeal_Adminhtml_SubscriberController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('groupdeal/subscribers')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Subscribers Manager'), Mage::helper('adminhtml')->__('Subscriber Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){return;}
		
		$collection = Mage::getModel('groupdeal/subscriber')->getCollection();
		$this->_title($this->__('Groupdeal'))->_title($this->__('Manage Subscribers'));
		$this->_initAction()
			->renderLayout();
	}
	
	public function gridAction(){
        $this->getResponse()->setBody($this->getLayout()->createBlock('groupdeal/adminhtml_subscriber_grid')->toHtml());
    }
	
	public function categoriesAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('groupdeal/adminhtml_subscriber_edit_tab_category')->toHtml()
        );
    }	
	
    public function categoriesJsonAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('groupdeal/adminhtml_subscriber_edit_tab_category')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }	
	
	public function editAction() {
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){return;}
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('groupdeal/subscriber')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			
			Mage::register('subscriber_data', $model);

			if($model->getId())
				$this->_title($this->__('Groupdeal'))->_title($this->__('Manage Subscribers'))->_title($this->__($model->getEmail()));
			else
				$this->_title($this->__('Groupdeal'))->_title($this->__('Manage Subscribers'))->_title($this->__('New Subscriber'));
				
			$this->loadLayout();
			$this->_setActiveMenu('groupdeal/subscribers');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Subscriber Manager'), Mage::helper('adminhtml')->__('Subscriber Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Subscriber News'), Mage::helper('adminhtml')->__('Subscriber News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			
			$this->_addContent($this->getLayout()->createBlock('groupdeal/adminhtml_subscriber_edit'))
				->_addLeft($this->getLayout()->createBlock('groupdeal/adminhtml_subscriber_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('groupdeal')->__('Subscriber does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		$dealId = $this->getRequest()->getParam('id');
		if ($data = $this->getRequest()->getPost()) {
		
			$categoryIds = explode(',', $this->getRequest()->getPost('category_ids'));
			$categoryIds = array_filter(array_unique($categoryIds));
			$data['categories'] = implode(',', $categoryIds);
			
			$model = Mage::getModel('groupdeal/subscriber');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				$model->save();				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('groupdeal')->__('Subscriber was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('groupdeal')->__('Unable to find subscriber to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('groupdeal/subscriber');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Subscriber was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $dealIds = $this->getRequest()->getParam('deal');
        if(!is_array($dealIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select subscriber(s)'));
        } else {
            try {
                foreach ($dealIds as $dealId) {
                    $deal = Mage::getModel('groupdeal/subscriber')->load($dealId);
                    $deal->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($dealIds)
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
        $subscriberIds = $this->getRequest()->getParam('subscriber');
        if(!is_array($subscriberIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select subscriber(s)'));
        } else {
            try {
                foreach ($subscriberIds as $subscriberId) {
                    $deal = Mage::getSingleton('groupdeal/subscriber')
                        ->load($subscriberId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
					$deal->setStatus();//auto set status
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($dealIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
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