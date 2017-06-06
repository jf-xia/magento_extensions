<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Guestbook
 * @version    1.1
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Guestbook_Block_Adminhtml_Message_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _helper()
    {
        return Mage::helper('guestbook');
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('guestbook_form', array('legend' => $this->_helper()->__('General')));

        $fieldset->addField('name', 'text', array(
            'label' => $this->_helper()->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
        ));

        $fieldset->addField('email', 'text', array(
            'label' => $this->_helper()->__('Email'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'email',
        ));

        $values = Mage::registry('current_comment')->getData();
        if (!isset($values['reply_to'])){
            $fieldset->addField('subject', 'text', array(
                'label' => $this->_helper()->__('Subject'),
                'required' => true,
                'class' => 'required-entry',
                'name' => 'subject',
            ));
        }

        $fieldset->addField('message', 'textarea', array(
            'label' => $this->_helper()->__('Comment'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'message',
        ));

        $comment = Mage::getSingleton('guestbook/message');

        $fieldset->addField('status', 'select',
            array(
                'name'      => 'status',
                'label'     => $this->_helper()->__('Status'),
                'values'    => $comment->toOptionArray(),
        ));

        if (!Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'select',
                array(
                    'label'     => $this->_helper()->__('Posted from'),
                    'required'  => true,
                    'name'      => 'store_id',
                    'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()
                ));
        }

        if (Mage::getSingleton('adminhtml/session')->getPostData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getPostData());
            Mage::getSingleton('adminhtml/session')->getPostData(null);
        } elseif (Mage::registry('current_comment')) {
            $form->setValues(Mage::registry('current_comment')->getData());
        }

        return parent::_prepareForm();
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_helper()->__("General");
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_helper()->__("General");
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}