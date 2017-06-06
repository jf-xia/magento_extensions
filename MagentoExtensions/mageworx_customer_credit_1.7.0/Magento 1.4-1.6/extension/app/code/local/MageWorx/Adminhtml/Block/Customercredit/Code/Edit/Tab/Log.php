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

class MageWorx_Adminhtml_Block_Customercredit_Code_Edit_Tab_Log extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setUseAjax(true);
        $this->setDefaultSort('action_date');
        $this->setDefaultDir('desc');
        $this->setId('logGrid');
    }
    
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('customercredit/code_log')
            ->getCollection()
            ->addCodeFilter(Mage::registry('current_customercredit_code')->getId());
        $this->setCollection($collection);
            
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn('action_date', array(
            'header'    => $this->_helper()->__('Modified On'),
            'index'     => 'action_date',
            'type'      => 'datetime',
            'width'     => 150,
        ));
        $this->addColumn('action_type', array(
            'header'    => $this->_helper()->__('Action'),
            'index'     => 'action_type',
            'type'      => 'options',
            'width'     => 130,
            'sortable'  => false,
            'options'   => Mage::getSingleton('customercredit/code_log')->getActionTypesOptions(),
        ));
        $this->addColumn('credit', array(
            'header'    => $this->_helper()->__('Credit'),
            'index'     => 'credit',
            'type'      => 'price',
            'width'     => 100,
            'sortable'  => false,
            'filter'    => false,
            'currency_code' => Mage::app()->getWebsite(Mage::registry('current_customercredit_code')->getWebsiteId())->getBaseCurrencyCode(),
        ));
        $this->addColumn('comment', array(
            'header'    => $this->_helper()->__('Comment'),
            'index'     => 'comment',
            'sortable'  => false,
        ));
        
        return parent::_prepareColumns();
    }
    
    /**
     * 
     * @return MageWorx_CustomerCredit_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('customercredit');
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/logGrid', array('_current'=> true));
    }
}