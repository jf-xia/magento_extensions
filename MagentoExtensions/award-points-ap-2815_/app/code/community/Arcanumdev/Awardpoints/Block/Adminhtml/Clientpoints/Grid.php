<?php
 /*
 * Arcanum Dev AwardPoints
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to arcanumdev@wafunotamago.com so we can send you a copy immediately.
 *
 * @category   Magento Sale Extension
 * @package    AwardPoints
 * @copyright  Copyright (c) 2012 Arcanum Dev. Y.K. (http://arcanumdev.wafunotamago.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Arcanumdev_Awardpoints_Block_Adminhtml_Clientpoints_Grid extends Mage_Adminhtml_Block_Widget_Grid{public function __construct(){parent::__construct();$this->setId('clientpointsGrid');$this->setDefaultSort('customer_id ');$this->setDefaultDir('DESC');$this->setSaveParametersInSession(true);}protected function _prepareCollection(){$collection=Mage::getResourceModel('awardpoints/awardpoints_collection');$this->setCollection($collection);return parent::_prepareCollection();}protected function _prepareColumns(){$this->addColumn('id',array('header'=>Mage::helper('awardpoints')->__('id'),'align'=>'right','width'=>'50px','index'=>'awardpoints_account_id',));$this->addColumn('client_id',array('header'=>Mage::helper('awardpoints')->__('Client'),'align'=>'right','index'=>'customer_id','width'=>'50px',));$this->addColumn('email',array('header'=>Mage::helper('awardpoints')->__('Email'),'align'=>'right','index'=>'email',));$this->addColumn('order_id',array('header'=>Mage::helper('awardpoints')->__('Order ID'),'align'=>'right','index'=>'order_id',));$this->addColumn('order_id_corres',array('header'=>Mage::helper('awardpoints')->__('Type of points'),'index'=>'order_id','width'=>'150px','type'=>'options','options'=>array(Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_REVIEW=>Mage::helper('adminhtml')->__('Review points'), Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_ADMIN=>Mage::helper('adminhtml')->__('Admin gift'), Arcanumdev_Awardpoints_Model_Stats::TYPE_POINTS_REGISTRATION=>Mage::helper('adminhtml')->__('Registration points')),));$this->addColumn('points_current',array('header'=>Mage::helper('awardpoints')->__('Accumulated points'),'align'=>'right','index'=>'points_current','width'=>'50px','filter'=>false,));$this->addColumn('points_spent',array('header'=>Mage::helper('awardpoints')->__('Spent points'),'align'=>'right','index'=>'points_spent','width'=>'50px','filter'=>false,));return parent::_prepareColumns();}protected function _prepareMassaction(){$this->setMassactionIdField('awardpoints_account_id');$this->getMassactionBlock()->setFormFieldName('awardpoints_account_ids');$this->getMassactionBlock()->addItem('delete',array( 'label'=>Mage::helper('awardpoints')->__('Delete&nbsp;&nbsp;'), 'url'=>$this->getUrl('*/*/massDelete'), 'confirm'=>Mage::helper('awardpoints')->__('Are you sure?')));return $this;}protected function _afterLoadCollection(){$this->getCollection()->walk('afterLoad');parent::_afterLoadCollection();}}