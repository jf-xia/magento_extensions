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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Stats_Grid extends Mage_Adminhtml_Block_Widget_Grid{public function __construct(){parent::__construct();$this->setId('statsGrid');$this->setDefaultSort('customer_id ');$this->setDefaultDir('DESC');$this->setSaveParametersInSession(true);}protected function _prepareCollection(){$collection = Mage::getResourceModel('awardpoints/customer_collection')->addNameToSelect();$this->setCollection($collection);return parent::_prepareCollection();}protected function _prepareColumns(){$this->addColumn('id',array('header'=>Mage::helper('awardpoints')->__('ID'),'align'=>'right','width'=>'50px','index'=>'entity_id',));$this->addColumn('name',array('header'=>Mage::helper('awardpoints')->__('Customer full name'),'align'=>'left','index'=>'name',));$this->addColumn('email',array('header'=>Mage::helper('awardpoints')->__('Customer email'),'align'=>'left','index'=>'email',));$this->addColumn('points_current',array('header'=>Mage::helper('awardpoints')->__('Accumulated points'),'align'=>'right','index'=>'all_points_accumulated','filter'=>false,));$this->addColumn('points_spent',array('header'=>Mage::helper('awardpoints')->__('Spent points'),'align'=>'right','index'=>'all_points_spent','filter'=>false,));$this->addExportType('*/*/exportCsv', Mage::helper('awardpoints')->__('CSV'));$this->addExportType('*/*/exportXml', Mage::helper('awardpoints')->__('XML'));return parent::_prepareColumns();}}