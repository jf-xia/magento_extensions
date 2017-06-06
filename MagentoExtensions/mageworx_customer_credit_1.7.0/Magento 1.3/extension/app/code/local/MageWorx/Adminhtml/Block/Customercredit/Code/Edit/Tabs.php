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
 
class MageWorx_Adminhtml_Block_Customercredit_Code_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('customercredit_code_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('customercredit')->__('Recharge Code'));
    }
    
    protected function _beforeToHtml()
    {
        $codeModel = Mage::registry('current_customercredit_code');
        if ($codeModel->getIsNew())
        {
            $this->addTab('settings_section', array(
                'label'     => Mage::helper('customercredit')->__('Settings'),
                'title'     => Mage::helper('customercredit')->__('Settings'),
                'content'   => $this->getLayout()->createBlock('mageworx/customercredit_code_edit_tab_settings')->toHtml(),
                'active'    => true,
            ));
        }
        $this->addTab('details_section', array(
            'label'     => Mage::helper('customercredit')->__('Details'),
            'title'     => Mage::helper('customercredit')->__('Details'),
            'content'   => $this->getLayout()->createBlock('mageworx/customercredit_code_edit_tab_details')->toHtml(),
            'active'    => $codeModel->getIsNew() ? false : true
        ));

        if (!$codeModel->getIsNew())
        {
            $this->addTab('log_section', array(
                'label'     => Mage::helper('customercredit')->__('Action Log'),
                'title'     => Mage::helper('customercredit')->__('Action Log'),
                'content'   => $this->getLayout()->createBlock('mageworx/customercredit_code_edit_tab_log')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }
}