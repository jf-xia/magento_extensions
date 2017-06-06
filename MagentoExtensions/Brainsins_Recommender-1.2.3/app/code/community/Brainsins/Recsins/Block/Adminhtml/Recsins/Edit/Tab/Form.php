<?php

/*
 * BrainSINS' Magento Extension allows to integrate the BrainSINS
 * personalized product recommendations into a Magento Store.
 * Copyright (c) 2011 Social Gaming Platform S.R.L.
 *
 * This file is part of BrainSINS' Magento Extension.
 *
 *  BrainSINS' Magento Extension is free software: you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  Foobar is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Please do not hesitate to contact us at info@brainsins.com
 *
 */

class Brainsins_Recsins_Block_Adminhtml_Recsins_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('recsins_form', array('legend' => Mage::helper('recsins')->__('Item information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('recsins')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('filename', 'file', array(
            'label' => Mage::helper('recsins')->__('File'),
            'required' => false,
            'name' => 'filename',
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('recsins')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('recsins')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('recsins')->__('Disabled'),
                ),
            ),
        ));

        $fieldset->addField('content', 'editor', array(
            'name' => 'content',
            'label' => Mage::helper('recsins')->__('Content'),
            'title' => Mage::helper('recsins')->__('Content'),
            'style' => 'width:700px; height:500px;',
            'wysiwyg' => false,
            'required' => true,
        ));

        if (Mage::getSingleton('adminhtml/session')->getRecsinsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getRecsinsData());
            Mage::getSingleton('adminhtml/session')->setRecsinsData(null);
        } elseif (Mage::registry('recsins_data')) {
            $form->setValues(Mage::registry('recsins_data')->getData());
        }
        return parent::_prepareForm();
    }

}