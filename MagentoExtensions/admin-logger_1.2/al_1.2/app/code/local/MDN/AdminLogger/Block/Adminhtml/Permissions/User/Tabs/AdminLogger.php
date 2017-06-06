<?php

class MDN_AdminLogger_Block_Adminhtml_Permissions_User_Tabs_AdminLogger 
extends Mage_Adminhtml_Block_Widget_Grid
implements Mage_Adminhtml_Block_Widget_Tab_Interface 
{

     /**
     * Constructor
     */
    public function __construct() {

        parent::__construct();
        $this->setId('user_adminlogger');
        $this->_parentTemplate = $this->getTemplate();
        $this->setEmptyText($this->__('No items'));
        $this->setDefaultSort('al_date');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
    }

    /**
     * Charge collection with filter for user infos
     *
     * @return unknown
     */
    protected function _prepareCollection()
    {		            

        $collection = Mage::getModel('AdminLogger/Log')
        	->getCollection()
                ->addFieldToFilter('al_object_id', array('eq' => $this->getRequest()->getParam('user_id') ) )
                ->addFieldToFilter('al_object_type', array('like' => '%user%' ) );
       
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
   /**
     * D�fini les colonnes du grid
     *
     * @return unknown
     */
    protected function _prepareColumns()
    {
                               
        $this->addColumn('al_date', array(
            'header'=> Mage::helper('AdminLogger')->__('Date'),
            'index' => 'al_date',
            'type' => 'datetime'
        ));

        $this->addColumn('al_user', array(
            'header'=> Mage::helper('AdminLogger')->__('User'),
            'index' => 'al_user'
        ));

        $this->addColumn('al_object_description', array(
            'header'=> Mage::helper('AdminLogger')->__('Object'),
            'index' => 'al_object_description'
        ));

        $this->addColumn('al_action_type', array(
            'header'=> Mage::helper('AdminLogger')->__('Action'),
            'index' => 'al_action_type',
            'type' => 'options',
            'options' => mage::getModel('AdminLogger/Log')->getActionTypes(),
            'renderer' => 'MDN_AdminLogger_Block_Widget_Grid_Column_Renderer_DisplayAction'
        ));

        $this->addColumn('al_description', array(
            'header'=> Mage::helper('AdminLogger')->__('Description'),
            'index' => 'al_description',
            'renderer' => 'MDN_AdminLogger_Block_Widget_Grid_Column_Renderer_DisplayDescription'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('AdminLogger/Admin/UserAjaxGrid', array('_current' => true));
    }

    public function getGridParentHtml()
    {
        $templateName = Mage::getDesign()->getTemplateFilename($this->_parentTemplate, array('_relative'=>true));
        return $this->fetchView($templateName);
    }
    


/**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel(){
        return $this->__('Admin Logger');
    }
 
    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle(){
        return $this->__('Click here to view the log for this product');
    }
 
    /**
     * Determines whether to display the tab
     * Add logic here to decide whether you want the tab to display
     *
     * @return bool
     */
    public function canShowTab(){
        return true;
    }
 
    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden(){
        return false;
    }

}
