<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G. ()
 */
class Soon_AdvancedCache_Block_Adminhtml_Blocks_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('blocksGrid');
        $this->setDefaultSort('block_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {

        $collection = Mage::getResourceSingleton('advancedcache/blocks_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('block_id', array(
            'header' => Mage::helper('advancedcache')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'block_id',
        ));
        $this->addColumn('identifier', array(
            'header' => Mage::helper('advancedcache')->__('Identifier (Tag)'),
            'width' => '100px',
            'index' => 'identifier',
        ));
        $this->addColumn('block_name', array(
            'header' => Mage::helper('advancedcache')->__('Block Name in Layout'),
            'width' => '250px',
            'index' => 'block_name',
        ));
        $this->addColumn('description', array(
            'header' => Mage::helper('advancedcache')->__('Description'),
            'index' => 'description',
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('advancedcache')->__('Status'),
            'width' => '120px',
            'index' => 'status',
            'type' => 'options',
            'options'   => array(0 => $this->__('Disabled'), 1 => $this->__('Enabled')),
            'frame_callback' => array($this, 'decorateStatus'),
        ));
        $this->addColumn('action', array(
            'header' => Mage::helper('adminhtml')->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getBlockId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('adminhtml')->__('Edit'),
                    'url' => array(
                        'base' => '*/*/edit',
                        'params' => array()
                    ),
                    'field' => 'block_id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
        ));
    }

    public function getRowUrl($row)
    {
		return false;
        //return $this->getUrl('*/*/edit', array('block_id' => $row->getBlockId()));
    }

    public function decorateStatus($value, $row, $column, $isExport)
    {
        $class = '';
        if (isset($this->_invalidatedTypes[$row->getId()])) {
            $cell = '<span class="grid-severity-minor"><span>'.$this->__('Invalidated').'</span></span>';
        } else {
            if ($row->getStatus()) {
                $cell = '<span class="grid-severity-notice"><span>'.$value.'</span></span>';
            } else {
                $cell = '<span class="grid-severity-critical"><span>'.$value.'</span></span>';
            }
        }
        return $cell;
    }    

    protected function _prepareMassaction() {
        $this->setMassactionIdField('block_id');
        $this->getMassactionBlock()->setFormFieldName('blocks');

        $this->getMassactionBlock()->addItem('disable', array(
            'label' => Mage::helper('adminhtml')->__('Disable'),
            'url' => $this->getUrl('*/*/massStatus', array('status' => 0)),
        ));
        
        $this->getMassactionBlock()->addItem('enable', array(
            'label' => Mage::helper('adminhtml')->__('Enable'),
            'url' => $this->getUrl('*/*/massStatus', array('status' => 1)),
        ));
        
        $this->getMassactionBlock()->addItem('refresh', array(
            'label' => Mage::helper('advancedcache')->__('Refresh'),
            'url' => $this->getUrl('*/*/massRefresh'),
            'selected' => true,
        ));

        return $this;
    }

}
