<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2009 Maison du Logiciel (http://www.maisondulogiciel.com)
 * @author : Olivier ZIMMERMANN
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MDN_AdminLogger_Block_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('AdminLoggerTaskGrid');
        $this->_parentTemplate = $this->getTemplate();
        //$this->setTemplate('Shipping/List.phtml');	
        $this->setEmptyText($this->__('No items'));
        $this->setDefaultSort('al_date');
        $this->setDefaultDir('DESC');

    }

    /**
     * Charge la collection
     *
     * @return unknown
     */
    protected function _prepareCollection()
    {		            
		//charge
        $collection = Mage::getModel('AdminLogger/Log')
        	->getCollection();
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

     public function getGridUrl()
    {
        return ''; //$this->getUrl('*/*/wishlist', array('_current'=>true));
    }

    public function getGridParentHtml()
    {
        $templateName = Mage::getDesign()->getTemplateFilename($this->_parentTemplate, array('_relative'=>true));
        return $this->fetchView($templateName);
    }

    public function getClearUrl()
    {
    	return $this->getUrl('AdminLogger/Admin/Clear');
    }

    public function getPruneUrl()
    {
    	return $this->getUrl('AdminLogger/Admin/Prune');
    }
}
