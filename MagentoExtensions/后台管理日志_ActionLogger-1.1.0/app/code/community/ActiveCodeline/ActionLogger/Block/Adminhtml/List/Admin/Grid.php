<?php
/**
 * ActiveCodeline_ActionLogger_Adminhtml_Admin_GridController
 *
 * @category    ActiveCodeline
 * @package     ActiveCodeline_ActionLogger
 * @author      Branko Ajzele (http://activecodeline.net)
 * @copyright   Copyright (c) Branko Ajzele
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ActiveCodeline_ActionLogger_Block_Adminhtml_List_Admin_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _construct()
    {
        parent::_construct();

        $this->setId('activecodeline_actionlogger_list_admin');

        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('activecodeline_actionlogger/admin_collection');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
      $this->addColumn('id', array(
            'header'=> Mage::helper('activecodeline_actionlogger')->__('Entry#'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'id'
        ));

        $this->addColumn('created', array(
            'header'=> Mage::helper('activecodeline_actionlogger')->__('Created'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'created'
        ));

        $this->addColumn('action_name', array(
            'header'=> Mage::helper('activecodeline_actionlogger')->__('Action name'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'action_name'
        ));

        $this->addColumn('controller_name', array(
            'header'=> Mage::helper('activecodeline_actionlogger')->__('Controller name'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'controller_name'
        ));

        $this->addColumn('client_ip', array(
            'header'=> Mage::helper('activecodeline_actionlogger')->__('Client IP'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'client_ip'
        ));

        $this->addColumn('controller_module', array(
            'header'=> Mage::helper('activecodeline_actionlogger')->__('Controller module'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'controller_module'
        ));

        $this->addColumn('user_id', array(
            'header'=> Mage::helper('activecodeline_actionlogger')->__('User#'),
            'width' => '20px',
            'type'  => 'text',
            'index' => 'user_id'
        ));

        $this->addColumn('username', array(
            'header'=> Mage::helper('activecodeline_actionlogger')->__('UserName#'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'username'
        ));
				
        if (Mage::helper('activecodeline_actionlogger')->_canViewLoggedRequestParams()) {
            $this->addColumn('params', array(
                'header'=> Mage::helper('activecodeline_actionlogger')->__('Params#'),
                'width' => '80px',
                'type'  => 'text',
                'index' => 'params',
                'renderer'  => 'activecodeline_actionlogger/adminhtml_list_renderer_param',
            ));
        }

        return parent::_prepareColumns();
    }

    /**
     * Return Grid URL for AJAX query
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/actionlogger_admin_grid/grid', array('_current'=>true));
    }
}
