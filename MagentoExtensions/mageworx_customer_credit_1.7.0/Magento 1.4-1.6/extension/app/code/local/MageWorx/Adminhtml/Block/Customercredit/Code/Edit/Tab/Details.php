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
 
class MageWorx_Adminhtml_Block_Customercredit_Code_Edit_Tab_Details extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$model = Mage::registry('current_customercredit_code');
		$form = new Varien_Data_Form();
		$form->setHtmlIdPrefix('code_details_');
		$form->setFieldNameSuffix('details');
		
		$fieldset = $form->addFieldset('base_fieldset', array('legend'=>$this->_helper()->__('Details')));
        
		if ($model->getId()) {
            $fieldset->addField('code_id', 'hidden', array(
                'name' => 'code_id',
            ));
            $fieldset->addField('code', 'label', array(
                'name'      => 'code',
                'label'     => $this->_helper()->__('Code'),
                'title'     => $this->_helper()->__('Code')
            ));
        }
        
        $fieldset->addField('credit', 'text', array(
            'label'     => $this->_helper()->__('Credit Value'),
            'title'     => $this->_helper()->__('Credit Value'),
            'name'      => 'credit',
            'class'     => 'validate-number',
            'required'  => true,
            'after_element_html'      => '<div id="customercredit_currency_code"></div>',
        ));
        $fieldset->addField('website_id', 'select', array(
            'name'      => 'website_id',
            'label'     => $this->_helper()->__('Website'),
            'title'     => $this->_helper()->__('Website'),
            'required'  => true,
            'values'    => Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(true),
        ));
        $fieldset->addField('is_active', 'select', array(
            'label'     => $this->_helper()->__('Is Active'),
            'title'     => $this->_helper()->__('Is Active'),
            'name'      => 'is_active',
            'required'  => true,
            'options'   => array(
                MageWorx_CustomerCredit_Model_Code::STATUS_ACTIVE => $this->_helper()->__('Yes'),
                MageWorx_CustomerCredit_Model_Code::STATUS_INACTIVE => $this->_helper()->__('No'),
            ),
        ));
        
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('from_date', 'date', array(
            'name'   => 'from_date',
            'label'  => $this->_helper()->__('From Date'),
            'title'  => $this->_helper()->__('From Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'format'       => $dateFormatIso,
        ));
        $fieldset->addField('to_date', 'date', array(
            'name'   => 'to_date',
            'label'  => $this->_helper()->__('To Date'),
            'title'  => $this->_helper()->__('To Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'format'       => $dateFormatIso,
        ));
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
	}
	
	protected function _prepareLayout()
	{
	    parent::_prepareLayout();
	    $jsCusrrency = $this->getLayout()->createBlock('core/template')->setTemplate('customercredit/currency_js.phtml');
	    $this->setChild('js_currency', $jsCusrrency);
	}
	
	protected function _toHtml()
	{
	    $html = parent::_toHtml();
	    return $html . $this->getChild('js_currency')->toHtml();
	}
	
    public function getWebsiteHtmlId()
    {
        return 'code_details_website_id';
    }
	
	/**
	 * 
	 * @return MageWorx_CustomerCredit_Helper_Data
	 */
	protected function _helper()
	{
	    return Mage::helper('customercredit');
	}
}