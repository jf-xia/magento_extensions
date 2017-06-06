<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * 
 * Artist Controller for Admin Panel
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Adminhtml_ArtistController extends Mage_Adminhtml_Controller_action
{
/**
 * Initialize the Layout
*/ 
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('artist/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		return $this;
	}   
/**
 * Load Layout - Artist Detials Grid 
*/ 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
/**
 * Load Layout - Artist Edit Page
*/ 
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('artist/artist')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('artist_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('artist/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('artist/adminhtml_artist_edit'))
				->_addLeft($this->getLayout()->createBlock('artist/adminhtml_artist_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('artist')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
/**
 * Load Layout - Artwork Edit Page
*/ 	
	public function editartworkAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('artist/artwork')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('artwork_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('artist/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('artist/adminhtml_artwork_edit'));
				//->_addLeft($this->getLayout()->createBlock('artist/adminhtml_artwork_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('artist')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 /**
 * Load Layout - Add New Artist
*/ 
	public function newAction() {
		$this->_forward('edit');
	}
 /**
 * Save Artist Detials
*/  
	public function saveAction() {

		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}
	  			
	  			
			$model = Mage::getModel('artist/artist');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('artist')->__('Item was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('artist')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
/**
 * Delete Artist Detials
*/  
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('artist/artist');
				 
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
/**
 * Delete Selected Artist Detials - as a mass action
*/
    public function massDeleteAction() {
        $artistIds = $this->getRequest()->getParam('artist');
        if(!is_array($artistIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($artistIds as $artistId) {
                    $artist = Mage::getModel('artist/artist')->load($artistId);
                    $artist->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($artistIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
 
/**
 * Export Artist Detials as a csv formated
*/  
    public function exportCsvAction()
    {
        $fileName   = 'artist.csv';
        $content    = $this->getLayout()->createBlock('artist/adminhtml_artist_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'artist.xml';
        $content    = $this->getLayout()->createBlock('artist/adminhtml_artist_grid')
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
	
/**
 * Create Simple and Configurable Product of the Artwork
*/ 	
public function saveartworkAction()
 {
    $artworkid = $this->getRequest()->getParam('id');
    $status = Mage::getModel('artist/artwork');
    $status = $status->status;
    $thylakproduct = Mage::getModel('artist/artwork');
    $postdata = $this->getRequest()->getPost();
    //print_r($postdata);exit;
    $thylakproduct->setData($postdata);
    $thylakproduct->setArtworkId($artworkid);
    $thylakproduct->setId($artworkid);
    $thylakproduct->save();
    if(($postdata['status']==1) && ($status!=1))
    {
        $imagename = $thylakproduct->imagename;
        $title = $thylakproduct->title;
        //$thylakproduct = Mage::getModel('artist/artwork');
		$thylakproduct->title = $title;
        for($i=0;$i<2;$i++){
		$thylakproduct->i = $i;
            $thylaksimpleproduct = $thylakproduct->createSimpleProduct(); 
            $pid[$i] = $thylaksimpleproduct; }
			$thylakproduct->pid = $pid;
        $thylakconfigurableproduct = $thylakproduct->createConfigurableProduct();    
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('artist')->__('Product was successfully created'));
    }
    else
    {
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('artist')->__('Artwork Information successfully Updated')); 
    }
	$this->_redirect('artist/adminhtml_artist/index');
 }
}