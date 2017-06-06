<?php
/**
 * J2T RewardsPoint2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@j2t-design.com so we can send you a copy immediately.
 *
 * @category   Magento extension
 * @package    RewardsPoint2
 * @copyright  Copyright (c) 2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Rewardpoints_Block_Adminhtml_Stats_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('statsGrid');
      $this->setDefaultSort('customer_id ');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      //$collection = Mage::getResourceModel('rewardpoints/customer_collection')->restrictRewardPoints()->addNameToSelect();
      $collection = Mage::getModel('rewardpoints/stats')->getCollection()
              ->addValidPoints(Mage::app()->getStore()->getId())
              ->addClientEntries()
              ->showCustomerInfo();
              /*->joinEavTablesIntoCollection('customer_id', 'customer')*/
              //->setCountAttribute('main_table.customer_id');//->addNameToSelect();
              //->addNameToSelect();
      
      /*echo $collection->getSelect()->__toString();
      die;*/
      
      
      $this->setCollection($collection);
      
      parent::_prepareCollection();
      return $this;      
  }

  protected function _prepareColumns()
  {
      $this->addColumn('id', array(
            'header'    => Mage::helper('rewardpoints')->__('ID'),
            'width'     => '50px',
            'index'     => 'customer_id',          
            'filter_index' =>'main_table.customer_id',
            'type'  => 'number',
        ));


      $this->addColumn('customer_firstname', array(
          'header'    => Mage::helper('rewardpoints')->__('Customer First Name'),
          'align'     => 'right',
          'index'     => 'customer_firstname',          
          'filter_index' =>'customer_firstname_table.value',          
      ));
      
      $this->addColumn('customer_lastname', array(
          'header'    => Mage::helper('rewardpoints')->__('Customer Last Name'),
          'align'     => 'right',
          'index'     => 'customer_lastname',          
          'filter_index' =>'customer_lastname_table.value',          
      ));
      
      
           
      
      $this->addColumn('email', array(
          'header'    => Mage::helper('rewardpoints')->__('Customer email'),
          'align'     => 'left',
          'index'     => 'email',
          'filter_index' =>'cust.email',
      ));
      
      
      $this->addColumn('nb_credit', array(
          'header'    => Mage::helper('rewardpoints')->__('Accumulated points'),
          'align'     => 'right',
          'index'     => 'nb_credit',
          'filter'    => false,
          'width'     => '50px',
      ));
      $this->addColumn('nb_credit_spent', array(
          'header'    => Mage::helper('rewardpoints')->__('Spent points'),
          'align'     => 'right',
          'index'     => 'nb_credit_spent',
          'filter'    => false,
          'width'     => '50px',
          //'sortable'    => false,
      ));
      
      $this->addColumn('nb_credit_available', array(
          'header'    => Mage::helper('rewardpoints')->__('Available points'),
          'align'     => 'right',
          'index'     => 'nb_credit_available',
          'filter'    => false,
          'width'     => '50px',
          //'sortable'    => false,
      ));
      
      

      $this->addExportType('*/*/exportCsv', Mage::helper('rewardpoints')->__('CSV'));
      $this->addExportType('*/*/exportXml', Mage::helper('rewardpoints')->__('XML'));
      
      return parent::_prepareColumns();
  }
}

