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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Referrals_Grid extends Mage_Adminhtml_Block_Widget_Grid{public function __construct(){parent::__construct();$this->setId('referralsGrid');$this->setDefaultSort('awardpoints_referral_id ');$this->setDefaultDir('DESC');$this->setSaveParametersInSession(true);}protected function _prepareCollection(){$collection = Mage::getResourceModel('awardpoints/referral_collection');$this->setCollection($collection);return parent::_prepareCollection();}protected function _prepareColumns(){$this->addColumn('id', array('header'=>Mage::helper('awardpoints')->__('id'),'align'=>'right','width'=>'50px','index'=>'awardpoints_referral_id',));$this->addColumn('email', array('header'=>Mage::helper('awardpoints')->__('Parent email'),'align'=>'right','index'=>'email',));$this->addColumn('awardpoints_referral_email', array('header'=>Mage::helper('awardpoints')->__('Referred email'),'align'=>'right','index'=>'awardpoints_referral_email',));$this->addColumn('awardpoints_referral_name', array('header'=>Mage::helper('awardpoints')->__('Referred Name'),'align'=>'right','index'=>'awardpoints_referral_name',));$this->addColumn('awardpoints_referral_status', array('header'=>Mage::helper('awardpoints')->__('Status'),'index'=>'awardpoints_referral_status','width'=>'150px','type'=>'options','options'=>array('1'=>Mage::helper('adminhtml')->__('Has ordered'),'0'=>Mage::helper('adminhtml')->__('Waiting for order')),));return parent::_prepareColumns();}protected function _prepareMassaction(){$this->setMassactionIdField('awardpoints_referral_id');$this->getMassactionBlock()->setFormFieldName('awardpoints_referral_ids');$this->getMassactionBlock()->addItem('delete', array('label'=>Mage::helper('awardpoints')->__('Delete&nbsp;&nbsp;'),'url'=>$this->getUrl('*/*/massDelete'), 'confirm'=>Mage::helper('awardpoints')->__('Are you sure?')));return $this;}protected function _afterLoadCollection(){$this->getCollection()->walk('afterLoad');parent::_afterLoadCollection();}}