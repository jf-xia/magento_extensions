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

class MageWorx_Adminhtml_Block_Customercredit_Code_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
    {
    	$this->_controller = 'customercredit_code';
		$this->_blockGroup = 'mageworx';
        parent::__construct();
        if ($this->getCode()->getIsNew())
            $this->_updateButton('save', 'label', Mage::helper('customercredit')->__('Generate'));
    }
    
    /**
     * 
     * @return MageWorx_CustomerCredit_Model_Code
     */
    public function getCode()
    {
    	return Mage::registry('current_customercredit_code');
    }
    
    public function getHeaderText()
    {
    	if (!$this->getCode()->getIsNew()) {
            return Mage::helper('customercredit')->__('Edit Recharge Code: %s', $this->htmlEscape($this->getCode()->getCode()));
        }
        else {
            return Mage::helper('customercredit')->__('Generate Recharge Codes');
        }
    }
}