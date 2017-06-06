<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G.
 */
class Soon_AdvancedCache_Block_Adminhtml_Exception_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('exceptionGrid');
        $this->setDefaultSort('exception_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {

        $collection = Mage::getResourceSingleton('advancedcache/exception_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {        
        
        $optionsSrc = Mage::getModel('adminhtml/system_config_source_cms_page')->toOptionArray();
        foreach($optionsSrc as $option) {
            $itemOptions[$option['value']] = $option['label'];
        }
        
        $this->addColumn('item_id', array(
                'header'=> Mage::helper('advancedcache')->__('Exception'),
                'index' => 'item_id',
                'type'  => 'options',
                'options' => $itemOptions,
        ));     
        
        $this->addColumn('action', array(
            'header' => Mage::helper('adminhtml')->__('Action'),
            'width' => '150px',
            'type' => 'action',
            'getter' => 'getExceptionId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('adminhtml')->__('Edit'),
                    'url' => array(
                        'base' => '*/*/edit',
                        'params' => array()
                    ),
                    'field' => 'exception_id'
                ),
                array(
                    'caption' => Mage::helper('adminhtml')->__('Delete'),
                    'url' => array(
                        'base' => '*/*/delete',
                        'params' => array()
                    ),
                    'field' => 'exception_id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
        ));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('exception_id' => $row->getExceptionId()));
    } 

    protected function _prepareMassaction() {
    }

}