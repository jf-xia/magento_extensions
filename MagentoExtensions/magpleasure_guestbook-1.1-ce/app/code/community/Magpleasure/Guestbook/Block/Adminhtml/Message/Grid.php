<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Guestbook
 * @version    1.1
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Guestbook_Block_Adminhtml_Message_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     * Helper
     * @return Magpleasure_Guestbook_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('guestbook');
    }


    public function __construct()
    {
        parent::__construct();
        $this->setId("guestbookGrid");
        $this->setDefaultSort("message_id");
        $this->setDefaultDir("DESC");
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("guestbook/message")->getCollection();
        $collection->addReplyTo();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('message_id', array(
            'header' => $this->_helper()->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'message_id',
        ));

        $this->addColumn('reply_to_text', array(
            'header' => $this->_helper()->__('Reply To'),
            'align' => 'left',
            'width' => '200px',
            'index' => 'reply_to_text',
            'renderer' => 'Magpleasure_Guestbook_Block_Adminhtml_Widget_Grid_Column_Renderer_Comment',
            'filter_condition_callback' => array($this, '_filterReplyToCondition'),
        ));

        $this->addColumn('message', array(
            'header' => $this->_helper()->__('Comment'),
            'align' => 'left',
            'width' => '200px',
            'renderer' => 'Magpleasure_Guestbook_Block_Adminhtml_Widget_Grid_Column_Renderer_Comment',
            'index' => 'message',
            'filter_condition_callback' => array($this, '_filterMessageCondition'),
        ));

        $this->addColumn('name', array(
            'header' => $this->_helper()->__('Name'),
            'align' => 'left',
            'index' => 'name',
        ));

        $this->addColumn('email', array(
            'header' => $this->_helper()->__('Email'),
            'align' => 'left',
            'index' => 'email',
        ));

        $this->addColumn('customer_id', array(
            'header' => $this->_helper()->__('Customer'),
            'align' => 'left',
            'index' => 'customer_id',
            'filter' => false,
            'renderer' => 'Magpleasure_Guestbook_Block_Adminhtml_Widget_Grid_Column_Renderer_Customer',
        ));

        $this->addColumn('status', array(
            'header' => $this->_helper()->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getModel('guestbook/message')->getOptionsArray(),
            'filter_condition_callback' => array($this, '_filterStatus'),
        ));

        if(!Mage::app()->isSingleStoreMode()){
            $this->addColumn('store_id', array(
                'header' => $this->__('Store View'),
                'index' => 'store_id',
                'sortable' => true,
                'width' => '120px',
                'type' => 'store',
                'store_view' => true,
                'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Store',
            ));
        }

        $this->addColumn('created_at', array(
            'header' => $this->_helper()->__('Created At'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '140px',
        ));

        $this->addColumn('updated_at', array(
            'header' => $this->_helper()->__('Updated At'),
            'index' => 'updated_at',
            'type' => 'datetime',
            'width' => '140px',
        ));

        $this->addColumn('action',
            array(
                'header' => $this->_helper()->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => $this->_helper()->__('View'),
                        'url' => array('base' => '*/*/view'),
                        'field' => 'id'
                    ),
                    array(
                        'caption' => $this->_helper()->__('Approve'),
                        'url' => array('base' => '*/*/approve'),
                        'field' => 'id'
                    ),
                    array(
                        'caption' => $this->_helper()->__('Reject'),
                        'url' => array('base' => '*/*/reject'),
                        'field' => 'id'
                    ),
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('message_id');
        $this->getMassactionBlock()->setFormFieldName('comments');

        $statuses = Mage::getModel('guestbook/message')->toOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => $this->_helper()->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => $this->_helper()->__('Status'),
                    'values' => $statuses
                )
            )
        ));

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->_helper()->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->_helper()->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid');
    }

    protected function _filterReplyToCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addReplyToTextFilter($value);
    }

    protected function _filterMessageCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addMessageTextFilter($value);
    }

    protected function _filterStatus($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStatusFilter($value);
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
            if ($column->getFilterConditionCallback()) {
                call_user_func($column->getFilterConditionCallback(), $this->getCollection(), $column);
            } else {
                $cond = $column->getFilter()->getCondition();
                if ($field && isset($cond)) {
                    $this->getCollection()->addFieldToFilter("main_table.".$field , $cond);
                }
            }
        }
        return $this;
    }

}