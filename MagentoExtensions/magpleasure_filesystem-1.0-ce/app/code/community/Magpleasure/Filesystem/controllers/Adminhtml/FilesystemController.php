<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Filesystem
 * @version    1.0
 * @copyright  Copyright (c) 2011 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Filesystem_Adminhtml_FilesystemController extends Mage_Adminhtml_Controller_action
{

    protected function _isAllowed() 
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/filesystem/edit');    
    }

    protected function _initAction() 
    {
		$this->loadLayout()
			->_setActiveMenu('filesystem/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('File Syslem'), Mage::helper('adminhtml')->__('IDE'));		
		return $this;
	}   
 
    /**
     * Response for Ajax Request
     * @param array $result
     */
    protected function _ajaxResponse($result = array())
    {
        $this->getResponse()->setBody(Zend_Json::encode($result));
    }        
    
	public function indexAction() 
    {
		$this->_initAction()
			->renderLayout();
	}
  
    /**
     * Helper
     * @return Magpleasure_Filesystem_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('filesystem');
    }


    /**
     * Session
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {                
        return Mage::getModel('adminhtml/session'); 
    }
    
    /**
     * File ids
     * @return array 
     */
    protected function _getFiles()
    {
        $files = array();
        
        if ($this->_getSession()->getFileInfo()){
            $files = $this->_getSession()->getFileInfo();
        } else {
            $this->_getSession()->setFileInfo($files);
        }                
        return $files;
    }
    
    /**
     * Save store files
     * @param array $files
     * @return Magpleasure_Filesystem_Adminhtml_FilesystemController 
     */
    protected function _setFiles($files)
    {
        $this->_getSession()->setFileInfo($files);
        return $this;
    }
    
    /**
     * Is file opened
     * 
     * @param string $path
     * @return  boolean
     */
    protected function _isFileOpened($path)
    {
        if (is_numeric($path)){
            return !!$this->_getPath($path);
        } else {        
            return !!$this->_getFile($path);
        }
    }
    
    /**
     * Open file, save identifier
     * 
     * @param string $path
     * @return int 
     */
    protected function _openFile($path)
    {
        $id = 0;
        if (!$this->_isFileOpened($path)){            
            $files = $this->_getFiles();
            $id = rand();
            $files[$id] = $path;           
            $this->_setFiles($files);            
        }
        return $id;
    }
    
    /**
     * Close file
     * @param type $id
     * @return Magpleasure_Filesystem_Adminhtml_FilesystemController 
     */
    protected function _closeFile($id)
    {
        if ($this->_isFileOpened($id)){
            $files = $this->_getFiles();
            if (isset($files[$id])){
                unset($files[$id]);
            }
            $this->_setFiles($files);            
        }        
        return $this;
    }
    
    /**
     * Get file path
     * @param  $file
     * @return string|boolean
     */
    protected function _getPath($file)
    {
        $files = $this->_getFiles();        
        if (isset($files[$file]) && $files[$file]){
            return $files[$file];
        }                
        return false;
    }
            
    /**
     * Get file id
     * @param  $file
     * @return int|boolean
     */
    protected function _getFile($path)
    {
        $files = $this->_getFiles();
        foreach ($files as $_file=>$_path){
            if ($path == $_path){
                return $_file;
            }            
        }                
        return false;
    }
                    
    public function treeAction()
    {
        $dir = $this->getRequest()->getPost('dir');
        $root = '';
        
        
        $dir = urldecode($dir);
        $response = "";

        if( file_exists($root . $dir) ) {
            $files = scandir($root . $dir);
            natcasesort($files);
            if( count($files) > 2 ) { /* The 2 accounts for . and .. */
                $response .= "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                // All dirs
                foreach( $files as $file ) {
                    if( file_exists($root . $dir . $file) && $file != '.' && $file != '..' && is_dir($root . $dir . $file) ) {
                        $response .= "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($dir . $file) . "/\">" . htmlentities($file) . "</a></li>";
                    }
                }
                // All files
                foreach( $files as $file ) {
                    if( file_exists($root . $dir . $file) && $file != '.' && $file != '..' && !is_dir($root . $dir . $file) ) {
                        $ext = preg_replace('/^.*\./', '', $file);
                        $response .= "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($dir . $file) . "\">" . htmlentities($file) . "</a></li>";
                    }
                }
                $response .= "</ul>";	
            }
        }    
        
        $this->getResponse()->setBody($response);
    }
    
    protected function _preparePathToShow($path)
    {
        return (str_replace(Mage::getBaseDir()."/", "", $path));
    }
    
    public function loadAction()
    {
        $result = array();
        if ($filename = $this->getRequest()->getParam('fn')){
            $filename = base64_decode($filename);
            try {
                if (file_exists($filename)){
                    
                    if (!$this->_isFileOpened($filename)){
                        $path_parts = pathinfo($filename);

                        $content['id'] = $this->_openFile($filename);
                        $content['syntax'] = ($path_parts['extension'] == 'phtml') ? 'php' :  $path_parts['extension'];
                        $content['text'] = file_get_contents($filename);
                        $content['title'] = $path_parts['basename']; 
                        
                        $result['path'] = $this->_preparePathToShow($filename);                            
                        $result['success'] = true;
                        $result['content'] = Zend_Json_Encoder::encode($content);                                               
                    } else {
                        $result['error'] = $this->_helper()->__('This file is opened already');
                    }                    

                }    
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }                        
        }        
        $this->_ajaxResponse($result);
    }
    
    public function saveAction()
    {
        $result = array();        
        $content = $this->getRequest()->getPost('content');
        $file = $this->getRequest()->getParam('file');
        if ($file && $this->_isFileOpened($file) && !is_null($content)){
            try {
                file_put_contents($this->_getPath($file), $content);
                $result['success'] = true;                
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }                               
        } else {
            $result['error'] = $this->_helper()->__('Wrong file id');
        }        
        $this->_ajaxResponse($result);
    }
    
    public function closeAction()
    {
        $result = array();        
        $file = $this->getRequest()->getParam('file');       
        if ($file && $this->_isFileOpened($file)){
            $this->_closeFile($file);
            $result['success'] = true;
        } else {
            $result['error'] = $this->_helper()->__('Wrong file id');
        }        
        $this->_ajaxResponse($result);        
    }
    
    public function filesAction()
    {
        $result = array();         
        $files = $this->_getFiles();
        
        $_files = array();
                            
        try {
            foreach ($files as $_file=>$_path){
                if (file_exists($_path)){                
                    $path_parts = pathinfo($_path);

                    $content = array();
                    $content['id'] = $_file;
                    $content['syntax'] = ($path_parts['extension'] == 'phtml') ? 'php' :  $path_parts['extension'];
                    $content['text'] = file_get_contents($_path);
                    $content['title'] = $path_parts['basename'];            
                    $content['path'] = $this->_preparePathToShow($_path);            

                    $_files[] = Zend_Json_Encoder::encode($content);

                } else {
                    $this->_closeFile($_file);                
                }
            }            
            $result['files'] = $_files;
            $result['success'] = true;
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }
                            
        $this->_ajaxResponse($result);
    }
    
}