<?php
class Swatches_CustomOptions_Adminhtml_Swatches_CustomoptionsController 
    extends Mage_Adminhtml_Controller_Action
{
    protected $_imageInstance;

    public function uploadAction()
    {
        $productId = $this->getRequest()->getParam('id');
        if ($productId) {
            $backUrl = Mage::helper('adminhtml')
            ->getUrl('*/catalog_product/edit', array(
                'id'    => $productId,
                'tab'   => 'product_info_tabs_swatches_customoptions',
            ));
            
            $toDelete = Mage::app()->getRequest()->getPost('customoptions_swatches_delete');
            if ($toDelete) {
                $this->_getImageInstance()->deleteImages(array_keys($toDelete));
            }
            
            $files = isset($_FILES['customoptions_swatches']) ? $_FILES['customoptions_swatches'] : array();
            if ($files) {
                foreach ($files['name'] as $key => $file) {
                    if ($files['error'][$key] == UPLOAD_ERR_OK) {
                        try {
                            $uploader = new Varien_File_Uploader(array(
                                'name' => $file,
                                'tmp_name' => $files['tmp_name'][$key],
                            ));
                            $uploader->setAllowedExtensions($this->_getAllowedExtensions());
                            $uploader->setAllowRenameFiles(true);
                            $uploader->setFilesDispersion(false);
                            $uploader->save($this->_getDestinationFolder());

                            $this->_getImageInstance()
                                    ->addImage($key, $uploader->getUploadedFileName());
                        } catch (Exception $e) {
                            $this->_getSession()->addError($e->getMessage());
                        }
                    }
                }
                $this->_getImageInstance()->saveImages();
            }
        }
        else {
            $backUrl = Mage::helper('adminhtml')->getUrl('*/catalog_product/index');
            $this->_getSession()
                    ->addError('Images were not uploaded. Please try again.');
        }
        $this->_redirectUrl($backUrl);
    }
    
    protected function _getAllowedExtensions()
    {
        return Mage::helper('swatches_customoptions')->getAllowedExtensions();
    }
    
    protected function _getDestinationFolder()
    {
        return Mage::helper('swatches_customoptions')->getImagePath();
    }
    
    protected function _getImageInstance()
    {
        if (is_null($this->_imageInstance)) {
            $this->_imageInstance = Mage::getModel('swatches_customoptions/product_option_image');
        }
        return $this->_imageInstance;
    }
}
