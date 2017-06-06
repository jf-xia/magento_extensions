<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_Adminhtml_Block_Customercredit_Customer_Edit_Tab_Customercredit_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('creditLogGrid');
        $this->setDefaultSort('action_date');
        $this->setUseAjax(true);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('value', array(
            'header'    => Mage::helper('customercredit')->__('Credit Balance'),
            'index'     => 'value',
            'type'      => 'currency',
            'sortable'  => false,
            'filter'    => false,
            'width'     => '50px',
            'renderer'  => 'mageworx/customercredit_widget_grid_column_renderer_currency'
        ));
        $this->addColumn('value_change', array(
            'header'    => Mage::helper('customercredit')->__('Added/Deducted'),
            'index'     => 'value_change',
            'sortable'  => false,
            'filter'    => false,
            'width'     => '50px',
            'renderer'  => 'mageworx/customercredit_widget_grid_column_renderer_currency'
        ));
        $this->addColumn('website_id', array(
            'header'    => Mage::helper('customercredit')->__('Website'),
            'index'     => 'website_id',
            'type'      => 'options',
            'options'   => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(),
            'sortable'  => false,
            'width'     => '120px',
        ));
        $this->addColumn('action_date', array(
            'header'   => Mage::helper('customercredit')->__('Modified On'),
            'index'    => 'action_date',
            'type'     => 'datetime',
            'width'    => '150px',
            'filter'   => false,
        ));
        $this->addColumn('action_type', array(
            'header'    => Mage::helper('customercredit')->__('Action'),
            'width'     => '50px',
            'index'     => 'action_type',
            'sortable'  => false,
            'type'      => 'options',
            'options'   => Mage::getSingleton('customercredit/credit_log')->getActionTypesOptions(),
        ));
        $this->addColumn('comment', array(
            'header'    => Mage::helper('customercredit')->__('Comment'),
            'index'     => 'comment',
            'type'      => 'text',
            'nl2br'     => true,
            'sortable'  => false,
            'filter'   => false,
        ));
        
        return parent::_prepareColumns();
    }
    
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('customercredit/credit_log')
            ->getCollection()
            ->addCustomerFilter(Mage::registry('current_customer')->getId());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/logGrid', array('_current'=> true));
    }
}