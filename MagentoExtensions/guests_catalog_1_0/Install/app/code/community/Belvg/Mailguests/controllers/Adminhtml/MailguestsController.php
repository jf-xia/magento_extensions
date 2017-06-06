<?php
/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
 /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 *****************************************/
 /* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
 /***************************************
 *         DISCLAIMER   *
 *****************************************/
 /* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 *****************************************************
 * @category   Belvg
 * @package    Belvg_Mailguests
 * @copyright  Copyright (c) 2010 - 2011 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_Mailguests_Adminhtml_MailguestsController extends Mage_Adminhtml_Controller_Action
{

  protected function _initAction()

  {

      $this->loadLayout()

          ->_setActiveMenu('mailguests/items')

          ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

      return $this;

  }   

 

  public function indexAction() {

      $this->_initAction();   
      
      $this->_addContent($this->getLayout()->createBlock('mailguests/adminhtml_mailguests'));

      $this->renderLayout();

  }


  /**

   * Product grid for AJAX request.

   * Sort and filter result for example.

   */

  public function gridAction()

  {

    
  	$this->loadLayout();
      $this->getResponse()->setBody(
             $this->getLayout()->createBlock('mailguests/adminhtml_mailguests_grid')->toHtml()
      );
      

  }
  
  
  /**
     * Export customer grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'customersCSV.csv';
        
        $content    = $this->getLayout()->createBlock('mailguests/adminhtml_mailguests_grid')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export customer grid to XML format
     */
    public function exportXmlAction()
    {
        $fileName   = 'customersXML.xml';
        $content    = $this->getLayout()->createBlock('mailguests/adminhtml_mailguests_grid')
            ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }
  

}