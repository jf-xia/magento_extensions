<?php

class Inchoo_EmailCommunication_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _construct()
    {
        parent::_construct();

        $this->setId('email_communication_log_grid');
        $this->_defaultSort = 'log_id';
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('inchoo_email_communication/log_collection');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
      $this->addColumn('log_id', array(
            'header'=> Mage::helper('inchoo_email_communication')->__('Log#'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'log_id'
        ));

        $this->addColumn('created_at', array(
            'header'=> Mage::helper('inchoo_email_communication')->__('Created'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'created_at'
        ));

        $this->addColumn('status', array(
            'header'=> Mage::helper('inchoo_email_communication')->__('Status'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'status'
        ));

        $this->addColumn('to_email', array(
            'header'=> Mage::helper('inchoo_email_communication')->__('Send To'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'to_email'
        ));

        $this->addColumn('subject', array(
            'header'=> Mage::helper('inchoo_email_communication')->__('Subject'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'subject'
        ));

        /*
        $this->addColumn('body', array(
            'header'=> Mage::helper('inchoo_email_communication')->__('Body'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'body',
            'renderer'  => 'inchoo_email_communication/adminhtml_log_renderer_body'
        ));
        */

        return parent::_prepareColumns();
    }

    /**
     * Return Grid URL for AJAX query
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/email_communication_log/grid', array('_current'=>true));
    }
    
    public function getRowUrl($item)
    {
        return false;
    }    
}
