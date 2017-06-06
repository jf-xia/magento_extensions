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

class Belvg_Mailguests_Block_Adminhtml_Mailguests_Grid extends Mage_Adminhtml_Block_Widget_Grid

{

  public function __construct()
  {
      parent::__construct();
      $this->setId('mailguestsGrid');
	  $this->setUseAjax(true);
      $this->setDefaultSort('parent_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }



  protected function _prepareCollection()
  {
  	$prefix = Mage::getConfig()->getTablePrefix();
  	
  	$collection = Mage::getModel('mailguests/mailguests')->getCollection();	
	$collection->getSelect()->joinLeft($prefix . 'sales_flat_order_address', $prefix . 'sales_flat_order_address.parent_id = main_table.entity_id AND ' . $prefix . 'sales_flat_order_address.address_type = "shipping"' , array('region', 'region', 'postcode', 'street', 'city', 'telephone', 'parent_id', 'country_id'));	
	
	$collection->getSelect()->where('main_table.customer_id IS NULL');
	
	//$collection->getSelect()->group('main_table.customer_email');
	
	$this->setCollection($collection);
	
	return parent::_prepareCollection();
  }



  protected function _prepareColumns()
  {
  	
	parent::_prepareColumns();
	

      $this->addColumn('parent_id', array(

          'header'    => Mage::helper('mailguests')->__('ID'),

          'align'     =>'center',

          'width'     => '20px',

          'index'     => 'parent_id',
          
          'type'  => 'number',

      ));
      
      
      $this->addColumn('customer_email', array(

          'header'    => Mage::helper('mailguests')->__('Customer Email'),

          'align'     =>'left',

          'index'     => 'customer_email',

      ));
      
      
      
      
      
      
      
      $this->addColumn('customer_firstname', array(

          'header'    => Mage::helper('mailguests')->__('Customer Firstname'),

          'align'     =>'left',

          //'width'     => '200px',

          'index'     => 'customer_firstname',

      ));
      
      
      $this->addColumn('customer_lastname', array(

          'header'    => Mage::helper('mailguests')->__('Customer Lastname'),

          'align'     =>'left',

          //'width'     => '200px',

          'index'     => 'customer_lastname',

      ));
      
      
      
      
      $this->addColumn('region', array(

          'header'    => Mage::helper('mailguests')->__('Region'),

          'align'     =>'left',

          'width'     => '200px',

          'index'     => 'region',

      ));
      
      
      $this->addColumn('country_id', array(

          'header'    => Mage::helper('mailguests')->__('Country code'),

          'align'     =>'left',

          'width'     => '50px',

          'index'     => 'country_id',

      ));
      
      
      $this->addColumn('postcode', array(

          'header'    => Mage::helper('mailguests')->__('Postcode'),

          'align'     =>'left',

          'width'     => '50px',

          'index'     => 'postcode',

      ));
      
      
      $this->addColumn('street', array(

          'header'    => Mage::helper('mailguests')->__('Street'),

          'align'     =>'left',

          'width'     => '200px',

          'index'     => 'street',

      ));
      
      
      $this->addColumn('city', array(

          'header'    => Mage::helper('mailguests')->__('City'),

          'align'     =>'left',

          'width'     => '200px',

          'index'     => 'city',

      ));
      
      $this->addColumn('telephone', array(

          'header'    => Mage::helper('mailguests')->__('Telephone'),

          'align'     =>'left',

          'width'     => '50px',

          'index'     => 'telephone',

      ));
      
      
      $this->addColumn('created_at', array(

          'header'    => Mage::helper('mailguests')->__('Created'),

          'align'     =>'left',

          'width'     => '300px',

          'index'     => 'created_at',
          'type'      => 'datetime',

      ));
      
      $this->addExportType('*/*/exportCsv', Mage::helper('mailguests')->__('CSV'));
      $this->addExportType('*/*/exportXml', Mage::helper('mailguests')->__('Excel XML'));
      
      return parent::_prepareColumns();

  }

	public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=> true));
    }

  public function getRowUrl($row)
  {      
      return '';

  }

}