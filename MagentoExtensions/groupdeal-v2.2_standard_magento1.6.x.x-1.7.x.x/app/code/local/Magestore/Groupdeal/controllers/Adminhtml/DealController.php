<?php

class Magestore_Groupdeal_Adminhtml_DealController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('groupdeal/deals')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Deal Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){return;}
		//auto set status
		$collection = Mage::getModel('groupdeal/deal')->getCollection()
					->addFieldToFilter('deal_status', array('neq'=>'0'));
		
		foreach($collection as $item){
			$item->setStatus();
		}
		
		$deals = Mage::helper('groupdeal')->getSendMailUnreachedDeals();
		foreach($deals as $deal){
			Mage::helper('groupdeal')->sendCancelDealEmailToCustomers($deal);
		}
		
		$this->_title($this->__('Groupdeal'))->_title($this->__('Manage Deals'));
		$this->_initAction()
			->renderLayout();
	}
	
	public function gridAction(){
        $this->getResponse()->setBody($this->getLayout()->createBlock('groupdeal/adminhtml_deal_grid')->toHtml());
    }
	
	public function productsAction(){
		$this->loadLayout();
		$this->getLayout()->getBlock('deal.edit.tab.product')
            ->setProducts($this->getRequest()->getPost('oproduct', null));
        $this->renderLayout();
	
	}
	
	public function productsGridAction()
	{
		$this->loadLayout();
		$this->getLayout()->getBlock('deal.edit.tab.product')
            ->setProducts($this->getRequest()->getPost('oproduct', null));
        $this->renderLayout();
	}
	
	public function ordersAction(){
		$this->loadLayout();
		$this->getLayout()->getBlock('deal.edit.tab.order');
        $this->renderLayout();
	}
	
	public function editAction() {
		if(!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)){return;}
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('groupdeal/deal')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			
			if($model->getId()){
				$startDatetime = Mage::getModel('core/date')->date(NULL, $model->getStartDatetime());
				$endDatetime = Mage::getModel('core/date')->date(NULL, $model->getEndDatetime());		
				$model->setStartDatetime($startDatetime);
				$model->setEndDatetime($endDatetime);
			}
			
			Mage::register('deal_data', $model);

			if($model->getId())
				$this->_title($this->__('Groupdeal'))->_title($this->__('Manage Deals'))->_title($this->__($model->getDealTitle()));
			else
				$this->_title($this->__('Groupdeal'))->_title($this->__('Manage Deals'))->_title($this->__('New Deal'));
				
			$this->loadLayout();
			$this->_setActiveMenu('groupdeal/deals');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Deal Manager'), Mage::helper('adminhtml')->__('Deal Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Deal News'), Mage::helper('adminhtml')->__('Deal News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			
			if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) { 
				$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true); 
			}
			
			$this->_addContent($this->getLayout()->createBlock('groupdeal/adminhtml_deal_edit'))
				->_addLeft($this->getLayout()->createBlock('groupdeal/adminhtml_deal_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('groupdeal')->__('Deal does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		$dealId = $this->getRequest()->getParam('id');
		if ($data = $this->getRequest()->getPost()) {
			$imageData = $this->getRequest()->getPost('image_url');
			$positions = $imageData['position'];
			$deletes = $imageData['delete'];
			$images = $_FILES['image_url_0'];

			$imageUrls = $this->saveImages($images, $positions, $deletes);

			$productlist = array();
			if(isset($data['deal_product'])){ 	
				$productIds = array();
				parse_str($data['deal_product'], $productIds);
				$productIds = array_keys($productIds);
				foreach($productIds as $productId){
					$productlist[$productId] = array('qty' => 1, 'position' => '');
				}
			}else{
				if($dealId) {
					$collection = Mage::getModel('groupdeal/productlist')->getCollection()
						->addFieldToFilter('deal_id', $dealId);
					if(count($collection)) {
						foreach($collection as $item) {
							$productlist[$item->getProductId()] = array('qty'=>1, 'position'=>'');
						}
					}
				}
				$productIds = array(0);
			}

			$data['start_datetime'] = $this->formatDatetime($data['start_datetime']);
			$data['end_datetime'] = $this->formatDatetime($data['end_datetime']);
			
			$model = Mage::getModel('groupdeal/deal');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			$productId = 0;
			if($dealId){
				$productId = Mage::getModel('groupdeal/deal')->load($dealId)->getProductId();
			}
						

			try {
			
				//create groupdeal product
				$product = Mage::helper('groupdeal')->createGroupdealProduct($data['deal_title'],$data['short_description'],$data['full_description'], $imageUrls, $data['deal_price'], $data['deal_value'], $data['deal_status'], $data['featured'], $data['end_datetime'], $data['url_key'], $productlist, $productId);
				
				
				if($product && $product->getId())
					$model->setProductId($product->getId());
				else{
					Mage::getSingleton('adminhtml/session')->addError('Unable to save deal, please try again');			
					$this->_redirect('*/*/');
					return;
				}
			
				if ($model->getCreatedTime == NULL) {
					$model->setCreatedTime(now());
				}
				
				$model->save();
				$model->processUrlKey();//set url key
				Mage::getModel('catalog/product')->load($product->getId())->setSku('gd_' . $model->getId())->save();// set Sku
				
				Mage::helper('groupdeal')->createRewriteUrl($model);//create rewrite url
				
				$this->addImages($model, $imageUrls, $positions, $deletes);
				
				$model->setStatus();//auto set status by start time, end time, bought, min purchase, max purchase
				Mage::helper('groupdeal')->assignProductIdsToDeal($model, $productIds);//add product to deal
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('groupdeal')->__('Deal was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('groupdeal')->__('Unable to find deal to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('groupdeal/deal');
				$groupdeal = $model->load($id);
				if($productId = $groupdeal->getProductId()) {
					$product = Mage::getModel('catalog/product')->load($productId);
					if($product->getId())$product->delete();
				}	
				$groupdeal->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Deal was successfully deleted'));
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
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select deal(s)'));
        } else {
            try {
                foreach ($dealIds as $dealId) {
                    $deal = Mage::getModel('groupdeal/deal')->load($dealId);
					$product = Mage::getModel('catalog/product')->load($deal->getProductId());
					if($productId = $product->getId()) {
						$product->delete();
					}
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
        $dealIds = $this->getRequest()->getParam('deal');
        if(!is_array($dealIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select deal(s)'));
        } else {
            try {
                foreach ($dealIds as $dealId) {
                    $deal = Mage::getSingleton('groupdeal/deal')
                        ->load($dealId)
                        ->setDealStatus($this->getRequest()->getParam('deal_status'))
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
  
    public function exportCsvAction()
    {
        $fileName   = 'deal.csv';
        $content    = $this->getLayout()->createBlock('groupdeal/adminhtml_deal_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'deal.xml';
        $content    = $this->getLayout()->createBlock('groupdeal/adminhtml_deal_grid')
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
	
	protected function formatDatetime($time){
		$arrDatetime = explode(' ', $time);
		$arrDate = explode('/', $arrDatetime[0]);		
		$localStrDatetime = $arrDate[2] . '-' . $arrDate[0] . '-' . $arrDate[1] . ' ' . $arrDatetime[1] . ':00';
		$gmtIntDateTime = Mage::getModel('core/date')->gmtTimestamp($localStrDatetime);
		return date('y-m-d H:i:s', $gmtIntDateTime);
	}
	
	protected function processImage($imageName, $index){
		try {	
			$uploader = new Varien_File_Uploader("image_url_0[$index]");
			$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
			$uploader->setAllowRenameFiles(true);
			$uploader->setFilesDispersion(false);
			$path = Mage::getBaseDir('media') . DS . 'groupdeal' . DS;
			$result = $uploader->save($path, $imageName);
		} catch (Exception $e) {
	  		
		}

		return 'groupdeal/'.$result['file'];
	}
	
	protected function saveImages($images, $positions, $deletes){
		$imagesBeforeSave = $this->getImagesBeforeSave();
		$imageUrls = array();
		
		//image not delete
		$deleteImageIds = array_keys(array_filter($deletes));
		foreach($imagesBeforeSave as $image){
			if(!in_array($image->getId(), $deleteImageIds))
				$imageUrls[$image->getId()] = $image->getImageUrl();
		}
		
		//image upload
		foreach($images['name'] as $index => $name){
			if($name){
				$imageUrls[$index] = $this->processImage($name, $index);
			}
		}
		return $this->sortImageByPosition($imageUrls, $positions);
	}
	
	protected function sortImageByPosition($imageUrls, $positions){
		asort($positions);
		$resortImageUrls = array();
		foreach($positions as $index => $value){
			$resortImageUrls[$index] = $imageUrls[$index];
		}
		return $resortImageUrls;
	}
	
	protected function addImages($deal, $imageUrls, $positions, $deletes){
		// add images
		foreach($imageUrls as $index => $url){
			try{
				$order = $positions[$index];
				$image = Mage::getModel('groupdeal/image')
							->setDealId($deal->getId())
							->setImageUrl($url)
							->setSortOrder($order);
				if($index > 0)
					$image->setId($index);

				$image->save();
			}catch(Exception $e){
			}
		}
		
		// remove images
		foreach($deletes as $imageId => $value){
			if($value){
				try{
					$image = Mage::getModel('groupdeal/image')->setId($imageId)
							->delete();
				}catch(Exception $e){
				}
			}
		}
	}
	
	protected function getImagesBeforeSave(){
		$imageIds = array();
		$dealId = $this->getRequest()->getParam('id');
		$images = Mage::getModel('groupdeal/image')->getCollection()
					->addFieldToFilter('deal_id', $dealId);
		/* foreach($images as $image){
			$imageIds[] = $image->getId();
		} */
		return $images;
	}
	
	protected function addDealToCategories($dealId, $categoryIds){
		$categoryIdsBeforeSave = $this->getCategoryIdsBeforeSave();
		try{
			foreach($categoryIds as $categoryId){
				$index = array_search($categoryId, $categoryIdsBeforeSave);
				if($index === false){
					$model = Mage::getModel('groupdeal/dealcategory')
							->setDealId($dealId)
							->setCategoryId($categoryId)
							->save();
				}else{
					unset($categoryIdsBeforeSave[$index]);
				}
			}
			
			$categoryIdsNotSave = $categoryIdsBeforeSave;
			
			foreach($categoryIdsNotSave as $categoryId){
				$model = Mage::getModel('groupdeal/dealcategory')->getCollection()
						->addFieldToFilter('deal_id', $dealId)
						->addFieldToFilter('category_id', $categoryId)
						->getFirstItem()
						->delete();
			}
			
		}catch(Exception $e){
		}
	}
	
	protected function getCategoryIdsBeforeSave(){
		$categoryIds = array();
		$dealId = $this->getRequest()->getParam('id');
		$collection = Mage::getModel('groupdeal/dealcategory')->getCollection()
						->addFieldToFilter('deal_id', $dealId);
		foreach($collection as $item){
			$categoryIds[] = $item->getCategoryId();
		}
		
		return $categoryIds;
	}
}