<?php
 
class MDN_AdminLogger_Block_Adminhtml_Customer_View_Grid
extends Mage_Adminhtml_Block_Widget_Grid
implements Mage_Adminhtml_Block_Widget_Tab_Interface 
{
 
    /**
     * Set the template for the block
     *
     */
    public function __construct(){

        parent::__construct();
        $this->setId('AdminLoggerCustomerGrid');
        $this->_parentTemplate = $this->getTemplate();
        $this->setTemplate('AdminLogger/Catalog/Product/Grid.phtml');
        $this->setEmptyText($this->__('No items'));
        $this->setDefaultSort('al_date');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
       
    }


    /**
     * Charge collection with filter for order infos
     *
     * @return unknown
     */
    protected function _prepareCollection()
    {		            

        $collection = Mage::getModel('AdminLogger/Log')
        	->getCollection()
                ->addFieldToFilter('al_object_id', array('eq' => $this->getRequest()->getParam('id') ) )
                ->addFieldToFilter('al_object_type', array('like' => '%customer/%' ) );
       
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

    /**
     *
     * @return type 
     */
    public function getGridParentHtml()
    {
        $templateName = Mage::getDesign()->getTemplateFilename($this->_parentTemplate, array('_relative'=>true));
        return $this->fetchView($templateName);
    }

    /**
     * Url to refresh grid using ajax
     */
    public function getGridUrl() {
        return $this->getUrl('AdminLogger/Admin/SelectedAdminLoggerGrid', array('_current' => true, 'id' => $this->getRequest()->getParam('id')));
    }
    
     /**
     * ######################## TAB settings #################################
     */
    
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
 
    /**
     * AJAX TAB's
     * If you want to use an AJAX tab, uncomment the following functions
     * Please note that you will need to setup a controller to recieve
     * the tab content request
     *
     */
    /**
     * Retrieve the class name of the tab
     * Return 'ajax' here if you want the tab to be loaded via Ajax
     *
     * return string
     */
#   public function getTabClass()
#   {
#       return 'my-custom-tab';
#   }
 
    /**
     * Determine whether to generate content on load or via AJAX
     * If true, the tab's content won't be loaded until the tab is clicked
     * You will need to setup a controller to handle the tab request
     *
     * @return bool
     */
#   public function getSkipGenerateContent()
#   {
#       return false;
#   }
 
    /**
     * Retrieve the URL used to load the tab content
     * Return the URL here used to load the content by Ajax
     * see self::getSkipGenerateContent & self::getTabClass
     *
     * @return string
     */
#   public function getTabUrl()
#   {
#       return null;
#   }
 
}