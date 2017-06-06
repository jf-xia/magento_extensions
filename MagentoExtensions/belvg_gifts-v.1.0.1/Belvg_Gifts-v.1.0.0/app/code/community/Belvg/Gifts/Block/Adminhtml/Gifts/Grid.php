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
 * @package    Belvg_Gifts
 * @copyright  Copyright (c) 2010 - 2012 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */
 
class Belvg_Gifts_Block_Adminhtml_Gifts_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setId('giftGrid');
        $this->setDefaultSort('gift_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('gifts/gifts')->getCollection();
        if($this->getRequest()->getParam('store', 0))$collection->addFilter('store', $this->getRequest()->getParam('store', 0));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('gift_id', array(
                'header' => Mage::helper('gifts')->__('ID'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'gift_id',
        ));

        $this->addColumn('title', array(
                'header' => Mage::helper('gifts')->__('Title'),
                'align' => 'left',
                'index' => 'title',
        ));

        $this->addColumn('status', array(
                'header' => Mage::helper('gifts')->__('Status'),
                'align' => 'left',
                'width' => '80px',
                'index' => 'status',
                'type' => 'options',
                'options' => array(
                        1 => 'Enabled',
                        2 => 'Disabled',
                ),
        ));
        
        $this->addExportType('*/*/exportCsv', Mage::helper('gifts')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('gifts')->__('XML'));

        return parent::_prepareColumns();
    }
    
     protected function _prepareMassaction()
    {
        $this->setMassactionIdField('gift_id');
        $this->getMassactionBlock()->setFormFieldName('gifts');

        $this->getMassactionBlock()->addItem('delete', array(
                'label' => Mage::helper('gifts')->__('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('gifts')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('gifts/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
                'label' => Mage::helper('gifts')->__('Change status'),
                'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
                'additional' => array(
                        'visibility' => array(
                                'name' => 'status',
                                'type' => 'select',
                                'class' => 'required-entry',
                                'label' => Mage::helper('gifts')->__('Status'),
                                'values' => $statuses
                        )
                )
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId(), 'store' => $row->getStore()));
    }
 
}
