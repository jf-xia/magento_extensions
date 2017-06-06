<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
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
 * @package    AW_Productquestions
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */
class AW_Productquestions_Block_Adminhtml_Productquestions_Reply extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_mode = 'reply';
        $this->_objectId = 'id';
        $this->_blockGroup = 'productquestions';
        $this->_controller = 'adminhtml_productquestions';
        
        $this->_updateButton('save', 'label', $this->__('Save'));
        $this->_updateButton('delete', 'label', $this->__('Delete'));

        $this->_addButton('saveandcontinue', array(
            'label'     => $this->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_addButton('saveandemail', array(
            'label'     => $this->__('Save And Send Email'),
            'onclick'   => 'saveAndEmail()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('productquestions_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'productquestions_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'productquestions_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/1/');
            }

            function saveAndEmail(){
                editForm.submit($('edit_form').action+'sendEmail/1/');
            }
        ";
    }

    public function getHeaderText()
    {
        $data = Mage::registry('productquestions_data');
        if(!empty($data))
            return htmlspecialchars($this->__('Reply question #%d from %s <%s>',
                        $data['question_id'],
                        $data['question_author_name'],
                        $data['question_author_email']));
        else return $this->__('Question');
    }
}
