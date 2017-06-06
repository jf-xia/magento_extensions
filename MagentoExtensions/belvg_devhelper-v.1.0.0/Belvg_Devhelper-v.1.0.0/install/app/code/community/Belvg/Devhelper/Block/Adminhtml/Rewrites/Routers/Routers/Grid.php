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
 * *************************************** */
/* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
  /***************************************
 *         DISCLAIMER   *
 * *************************************** */
/* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 * ****************************************************
 * @category   Belvg
 * @package    Belvg_Devhelper
 * @author Pavel Novitsky <pavel@belvg.com>
 * @copyright  Copyright (c) 2010 - 2012 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_Devhelper_Block_Adminhtml_Rewrites_Routers_Routers_Grid extends Belvg_Devhelper_Block_Adminhtml_Devhelper_Grid {

    /**
     * Grid settings
     */           
    public function __construct() {
        parent::__construct();
        $this->setId('devhelperGrid');
        $this->_filterVisibility = false;
        $this->_pagerVisibility = false;
    }
    
    /**
     * Prepare collection
     * @return Mage_Adminhtml_Block_Widget
     */    
    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('devhelper/routers_collection');

        $filter = $this->_helper->getFilter();

        $collection->_prepare($filter);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Define grid columns
     * @return Mage_Adminhtml_Block_Widget
     */        
    protected function _prepareColumns() {
        $this->addColumn('direction', array(
                'header' => $this->_helper->__('Direction'),
                'align' => 'left',
                'index' => 'direction',
                'sortable' => false,
        ));

        $this->addColumn('from', array(
                'header' => $this->_helper->__('From'),
                'align' => 'left',
                'index' => 'from',
                'sortable' => false,
        ));

        $this->addColumn('to', array(
                'header' => $this->_helper->__('To'),
                'align' => 'left',
                'index' => 'to',
                'sortable' => false,
        ));

        return parent::_prepareColumns();
    }

    /**
     *
     * @param Mage_Catalog_Model_Product|Varien_Object $row
     * @return null
     */
    public function getRowUrl($row) {
        return null;
    }
}
