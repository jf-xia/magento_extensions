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
 
class MageWorx_Adminhtml_Block_Customercredit_Code_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('codeGrid');
		$this->setSaveParametersInSession(true);
		$this->setDefaultSort('code_id');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
        $this->setVarNameFilter('customercredit_code_filter');
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('code_id',
            array(
                'header'=> $this->_helper()->__('ID'),
                'width' => '50px',
                'index' => 'code_id',
        ));
        $this->addColumn('code',
            array(
                'header'=> $this->_helper()->__('Code'),
                'width' => '250px',
                'index' => 'code',
        ));
        $this->addColumn('credit',
            array(
                'header'=> $this->_helper()->__('Value'),
                'width' => '80px',
                'type'  => 'number',
            	//'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),
                'index' => 'credit',
                'renderer' => 'mageworx/customercredit_widget_grid_column_renderer_currency'
        ));
        $this->addColumn('website_id',
            array(
                'header'=> $this->_helper()->__('Website'),
                'type'  => 'options',
                'index' => 'website_id',
            	'options' => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(),
        ));
        /*$this->addColumn('created_date',
            array(
                'header'=> $this->_helper()->__('Date Created'),
                'width' => '50px',
                'type'  => 'date',
                'index' => 'created_date',
        ));*/
        $this->addColumn('from_date', 
            array(
                'header'    => $this->_helper()->__('Date Start'),
                'align'     => 'left',
                'width'     => '50px',
                'type'      => 'date',
                'index'     => 'from_date',
        ));

        $this->addColumn('to_date',
            array(
                'header'    => $this->_helper()->__('Date Expire'),
                'align'     => 'left',
                'width'     => '50px',
                'type'      => 'date',
                'default'   => '--',
                'index'     => 'to_date',
        ));
        $this->addColumn('used_date',
            array(
                'header'=> $this->_helper()->__('Last Used'),
                'width' => '50px',
                'type'  => 'date',
                'index' => 'used_date',
            	'default' => '-',
        ));
        $this->addColumn('is_active',
            array(
                'header'=> $this->_helper()->__('Is Active'),
                'width' => 30,
                'type'  => 'options',
                'index' => 'is_active',
            	'options' => array(
            		MageWorx_CustomerCredit_Model_Code::STATUS_ACTIVE => $this->_helper()->__('Yes'),
            		MageWorx_CustomerCredit_Model_Code::STATUS_INACTIVE => $this->_helper()->__('No'),
            	),
        ));
        
        return parent::_prepareColumns();
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('customercredit/code_collection');
		$this->setCollection($collection);
        return parent::_prepareCollection();
	}
	
	public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    
	public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'id'    => $row->getId()
        ));
    }
    
    protected function _helper()
    {
    	return Mage::helper('customercredit');
    }
}