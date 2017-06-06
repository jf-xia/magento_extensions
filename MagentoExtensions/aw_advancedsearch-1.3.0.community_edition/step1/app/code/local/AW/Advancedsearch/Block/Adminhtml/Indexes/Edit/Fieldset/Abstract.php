<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedsearch
 * @version    1.3.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

abstract class AW_Advancedsearch_Block_Adminhtml_Indexes_Edit_Fieldset_Abstract extends Mage_Adminhtml_Block_Template
{
    protected function _toHtml()
    {
        $data = $this->_getDataObject();

        $form = new Varien_Data_Form();
        $form->setElementRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_element')
        );
        $form->setFieldsetRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')
        );
        $form->setFieldsetElementRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset_element')
        );

        $fieldset = $form->addFieldset('attributes_fieldset', array('legend' => $this->__('Attributes')));
        $fieldset->setFieldsetContainerId('awas_attributes');

        $_renderer = $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate($this->_getTemplate())
            ->setValues($data)
            ->setData('index_attributes', $this->_getIndexAttributes());
        $fieldset->setName('attributes')
            ->setRenderer($_renderer);

        return $form->getHtml();
    }

    protected function _getTemplate()
    {
        return 'aw_advancedsearch/form/renderer/fieldset/attributes.phtml';
    }

    abstract protected function _getIndexAttributes();
    abstract protected function _getDataObject();
}
