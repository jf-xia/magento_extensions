<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedsearch
 * @version    1.3.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Advancedsearch_Block_Adminhtml_Indexes_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('awasIndexesGrid');
        $this->setDefaultSort('type');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('awadvancedsearch/catalogindexes')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper('awadvancedsearch');
        
        $this->addColumn('id', array('header' => $helper->__('ID'),
                                     'index' => 'id',
                                     'width' => '100px'));
        $this->addColumn('type', array('header' => $helper->__('Type'),
                                       'index' => 'type',
                                       'type' => 'options',
                                       'options' => Mage::getModel('awadvancedsearch/source_catalogindexes_types')->toShortOptionArray()));
        $this->addColumn('last_update', array('header' => $helper->__('Last updated'),
                                              'index' => 'last_update',
                                              'width' => '200px',
                                              'type' => 'date',
                                              'renderer' => 'AW_Advancedsearch_Block_Widget_Grid_Column_Renderer_Datetime'));
        $this->addColumn('state', array('header' => $helper->__('State'),
                                         'index' => 'state',
                                         'width' => '150px',
                                         'type' => 'options',
                                         'align' => 'center',
                                         'options' => Mage::getModel('awadvancedsearch/source_catalogindexes_state')->toShortOptionArray(),
                                         'renderer' => 'AW_Advancedsearch_Block_Widget_Grid_Column_Renderer_State'));
        if(!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store', array('header' => $this->__('Store View'),
                                            'width'        => '150',
                                            'index' => 'store',
                                            'sortable' => FALSE,
                                            'type' => 'store',
                                            'store_all' => TRUE,
                                            'store_view' => TRUE,
                                            'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Store',
                                            'filter_condition_callback' => array($this, '_filterStoreCondition')));
        }
        if(Mage::helper('awadvancedsearch')->isEditAllowed()) {
            $this->addColumn('action', array('header'    => $this->__('Action'),
                                             'width'     => '100px',
                                             'align'     => 'center',
                                             'type'      => 'action',
                                             'getter'    => 'getId',
                                             'actions'   => array(array('caption'   => $this->__('Reindex'),
                                                                        'url'       => array('base'=> '*/*/reindex'),
                                                                        'field'     => 'id')),
                                             'filter'    => false,
                                             'sortable'  => false,
                                             'index'     => 'stores',
                                             'is_system' => true));
        }
        
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit/', array('id' => $row->getId()));
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if(!($value = $column->getFilter()->getValue())) return;
        $collection->addStoreFilter($value);
    }
}
