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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Catalogpointrules_Grid extends Mage_Adminhtml_Block_Widget_Grid{public function __construct(){parent::__construct();$this->setId('rule_id');$this->setDefaultSort('name');$this->setDefaultDir('ASC');$this->setSaveParametersInSession(true);}protected function _prepareCollection(){$collection=Mage::getModel('awardpoints/catalogpointrules')->getResourceCollection();$this->setCollection($collection);return parent::_prepareCollection();}protected function _prepareColumns(){$model=Mage::getModel('awardpoints/catalogpointrules');$this->addColumn('rule_id',array('header'=>Mage::helper('awardpoints')->__('ID'),'align'=>'right','width'=>'50px','index'=>'rule_id',));$this->addColumn('title',array('header'=>Mage::helper('awardpoints')->__('Title'),'align'=>'left','index'=>'title',));$this->addColumn('action_type',array('header'=>Mage::helper('awardpoints')->__('Action type'),'align'=>'left','index'=>'action_type','type'=>'options','options' =>$model->ruleActionTypesToArray(),));$this->addColumn('status',array('header'=>Mage::helper('awardpoints')->__('Status'),'align'=>'left','width'=>'100px','index'=>'status','type'=>'options','options' =>array(1=>'Active',0=>'Inactive',),));return parent::_prepareColumns();}public function getRowUrl($row){return $this->getUrl('*/*/edit',array('id'=>$row->getRuleId()));}}