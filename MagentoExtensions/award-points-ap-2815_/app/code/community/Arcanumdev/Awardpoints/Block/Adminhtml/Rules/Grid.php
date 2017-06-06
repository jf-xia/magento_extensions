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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid{public function __construct(){parent::__construct();$this->setId('rulesGrid');$this->setDefaultSort('awardpoints_rule_id ');$this->setDefaultDir('DESC');$this->setSaveParametersInSession(true);}protected function _prepareCollection(){$collection = Mage::getResourceModel('awardpoints/rules_collection');$this->setCollection($collection);return parent::_prepareCollection();}protected function _prepareColumns(){$this->addColumn('id',array('header'=>Mage::helper('awardpoints')->__('id'),'align'=>'right','width'=>'50px','index'=>'awardpoints_rule_id',));$this->addColumn('awardpoints_rule_name',array('header'=>Mage::helper('awardpoints')->__('Name'),'align'=>'right','index'=>'awardpoints_rule_name',));$this->addColumn('awardpoints_rule_activated',array('header'=>Mage::helper('awardpoints')->__('Status'),'index'=>'awardpoints_rule_activated','width'=>'50px','type'=>'options','options'=>array('1'=>Mage::helper('adminhtml')->__('Active'),'0'=>Mage::helper('adminhtml')->__('Inactive')),));$this->addColumn('admin_action',array('header'=>Mage::helper('awardpoints')->__('Action'),'width'=>'100','type'=>'action','getter'=>'getId','actions'=>array(array('caption'=>Mage::helper('awardpoints')->__('Edit'),'url'=>array('base'=>'*/*/edit'),'field'=>'id')),'filter'=>false,'sortable'=>false,'is_system'=>true,));return parent::_prepareColumns();}protected function _prepareMassaction(){$this->setMassactionIdField('awardpoints_rule_id');$this->getMassactionBlock()->setFormFieldName('awardpoints_rule_ids');$this->getMassactionBlock()->addItem('delete',array('label'=>Mage::helper('awardpoints')->__('Delete&nbsp;&nbsp;'),'url'=>$this->getUrl('*/*/massDelete'),'confirm'=>Mage::helper('awardpoints')->__('Are you sure?')));return $this;}protected function _afterLoadCollection(){$this->getCollection()->walk('afterLoad');parent::_afterLoadCollection();}public function getRowUrl($row){return $this->getUrl('*/*/edit',array('id'=>$row->getId()));}}