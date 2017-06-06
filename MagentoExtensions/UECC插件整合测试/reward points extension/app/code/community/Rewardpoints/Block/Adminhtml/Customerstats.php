<?php
/**
 * J2T RewardsPoint2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@j2t-design.com so we can send you a copy immediately.
 *
 * @category   Magento extension
 * @package    RewardsPoint2
 * @copyright  Copyright (c) 2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Rewardpoints_Block_Adminhtml_Customerstats extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_stats_grid');
        $this->setDefaultSort('rewardpoints_account_id', 'desc');
        $this->setUseAjax(true);
        
        $this->setEmptyText(Mage::helper('rewardpoints')->__('No Points Found'));
    }
    
    
    public function getGridUrl()
    {
        return $this->getUrl('rewardpoints/adminhtml_customerstats', array('_current'=>true));
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('rewardpoints/stats_collection')
             ->addFieldToFilter('customer_id', Mage::registry('current_customer')->getId());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /*protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('sales/order_grid_collection')
            ->addFieldToSelect('entity_id')
            ->addFieldToSelect('increment_id')
            ->addFieldToSelect('customer_id')
            ->addFieldToSelect('created_at')
            ->addFieldToSelect('grand_total')
            ->addFieldToSelect('order_currency_code')
            ->addFieldToSelect('store_id')
            ->addFieldToSelect('billing_name')
            ->addFieldToSelect('shipping_name')
            ->addFieldToFilter('customer_id', Mage::registry('current_customer')->getId())
        ;
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }*/

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
          'header'    => Mage::helper('rewardpoints')->__('ID'),
          'align'     =>'right',
          'width'     => '100px',
          'index'     => 'rewardpoints_account_id',
        ));

        
        $this->addColumn('order_id', array(
            'header'    => Mage::helper('rewardpoints')->__('Points type'),
            'align'     => 'left',
            'index'     => 'order_id',
            'type'    => 'action',
            'renderer' => new Rewardpoints_Block_Adminhtml_Renderer_Pointstype(),
        ));

        $this->addColumn('points_current', array(
          'header'    => Mage::helper('rewardpoints')->__('Accumulated points'),
          'align'     => 'right',
          'index'     => 'points_current',
          'filter'    => false,
        ));
        $this->addColumn('points_spent', array(
          'header'    => Mage::helper('rewardpoints')->__('Spent points'),
          'align'     => 'right',
          'index'     => 'points_spent',
          'filter'    => false,
        ));


        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('rewardpoints')->__('Stores'),
                'align'     => 'left',
                'index'     => 'store_id',
                'type'    => 'action',
                'renderer' => new Rewardpoints_Block_Adminhtml_Renderer_Store(),
            ));
        }

        //rewardpoints_referral_id
        $this->addColumn('rewardpoints_referral_id', array(
            'header'    => Mage::helper('rewardpoints')->__('Referred customer'),
            'align'     => 'left',
            'index'     => 'rewardpoints_referral_id',
            'type'    => 'action',
            'renderer' => new Rewardpoints_Block_Adminhtml_Renderer_Referral(),
        ));


        /*if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('customer')->__('Bought From'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view' => true
            ));
        }

        $this->addColumn('action', array(
            'header'    => ' ',
            'filter'    => false,
            'sortable'  => false,
            'width'     => '100px',
            'renderer'  => 'adminhtml/sales_reorder_renderer_action'
        ));*/

        return parent::_prepareColumns();
    }

    //public function getRowUrl($row)
    //{
    //    return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
    //}

    //public function getGridUrl()
    //{
    //    return $this->getUrl('*/*/orders', array('_current' => true));
    //}
}