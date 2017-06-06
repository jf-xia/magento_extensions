<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * 
 * Artist Module
 * 
 * Front End Controller for Artist Module
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_IndexController extends Mage_Core_Controller_Front_Action
{
/**
 * Load Layout - Login Page - Login.phtml
*/    
    public function indexAction()
    {
        $_SESSION['id'] = "";
 	    $this->loadLayout();     
		$this->renderLayout();
    }
/**
 * Load Layout - Register Page - register.phtml
*/  
	public function createAction()
	{
        $_SESSION['id'] = "";
		$this->loadLayout();
		$this->renderLayout();
		
	}
/**
 * Load Layout - Artwork Page - artwork.phtml
*/   
	public function artworkAction()
	{
		if($_SESSION['id'] != "") {
		$this->loadLayout();
		$this->renderLayout(); 
		} else {
		return $this->_redirect('*/*/index'); 
		}
		
	}
/**
 * Load Layout - Artist Page - artist.phtml
*/     
    public function artistAction()
    {
        if($_SESSION['id'] != "") {
        $this->loadLayout();
        $this->renderLayout(); 
        } else {
        return $this->_redirect('*/*/index'); 
        }
        
    }    
/**
 * Verify Login Details & login into artwork page
 *
*/    
	public function loginPostAction()
	{

	$collection  = Mage::getModel('artist/artist')->getCollection();
	foreach ($collection as $item) {
        if(($item->email == $this->getRequest()->getPost('email'))&&($item->password == $this->getRequest()->getPost('pass')))
		{
		
			$_SESSION['id'] = $item->artist_id;
			Mage::getSingleton('core/session')->addSuccess('LoginSuccess'); 
			return $this->_redirect('*/*/artist/id/'.$item->artist_id); 
		}
    }
			Mage::getSingleton('core/session')->addSuccess('LoginFailed'); 
			return $this->_redirect('*/*/index'); 		
	}
/**
 * Create a New Account
 *
*/	 
	public function postCreateAction()
	{
	$collection  = Mage::getModel('artist/artist')->getCollection();
	foreach ($collection as $item) {
        if($item->email == $this->getRequest()->getPost('email'))
		{
			Mage::getSingleton('core/session')->addError('Artist email already available'); 
			return $this->_redirect('*/*/index'); 
		}
    }
	$artist  = Mage::getModel('artist/artist');
	$postdata = $this->getRequest()->getPost();
	$artist->setData($postdata);
    $artist->setCreatedTime(now()); 
    $artist->setUpdateTime(now());  
	$artist->save();  
    $_SESSION['id'] = $artist->getData('artist_id') ;
	Mage::getSingleton('core/session')->addSuccess('Artist information saved successfully'); 
	 return $this->_redirect('*/*/artist',  array('id' => $_SESSION['id']));  
	}
/**
 * Save Artwork Details 
 *
*/	
	public function artworksaveAction()
	{
	$imagename = $_FILES['imagename']['name'];
	$artwork  = Mage::getModel('artist/artwork');
	$title = $this->getRequest()->getPost('title');
	$artistid = $this->getRequest()->getPost('artist_id');
	$artwork->setImagename($imagename);
	$artwork->setArtistId($artistid);
	$artwork->setTitle($title);
	$artwork->artistid = $artistid;
	$artwork->imagename = $imagename;
	$uploadartworkimage = $artwork->uploadArtwork();
   /* if(isset($_FILES['imagename']['name']) && $_FILES['imagename']['name'] != '') {
    try {    
                    /* Starting upload */    
                //    $uploader = new Varien_File_Uploader('imagename');
                    
                    // We set media as the upload dir
              //      $path = Mage::getBaseDir('media') . DS . $artistid . DS;
                 //   $uploader->save($path, $_FILES['imagename']['name'] );
                   
            /*    } catch (Exception $e) {
              
                }  
    }*/
    $artwork->setCreatedTime(now());
    $artwork->setUpdateTime(now());  
	$artwork->save();  
	Mage::getSingleton('core/session')->addSuccess('Artwork image uploaded successfully'); 
	 return $this->_redirect('*/*/artwork/id/'.$artistid.'/'); 
	} 	

/**
 * Save Artist Details 
 *
*/    
    public function artistsaveAction()
    {
        $id = $_SESSION['id'];
        $artist  = Mage::getModel('artist/artist');
        $artist->setData($this->getRequest()->getPost());
        $artist->setUpdateTime(now());
        $artist->setId($id);
        $artist->save();
        Mage::getSingleton('core/session')->addSuccess('Artist information updated successfully'); 
        return $this->_redirect('*/*/artist/id/'.$id.'/');         
    }    
   
}