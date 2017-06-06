<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G. 
 */
class Soon_AdvancedCache_Block_Adminhtml_Blocks_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_actionsController;
    
    public function __construct()
    {
        $this->_objectId = 'block_id';
        $this->_controller = 'adminhtml_blocks';
        $this->_actionsController = 'advancedcache_blocks';
        $this->_blockGroup = 'advancedcache';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('advancedcache')->__('Save Block'));
        $this->_updateButton('delete', 'label', Mage::helper('advancedcache')->__('Delete Block'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_addButton('cleanblockcache', array(
            'label'     => Mage::helper('advancedcache')->__('Clean Cache'),
            'onclick'   => 'cleanBlockCache()',
            'class'     => 'show-hide',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
            
            function cleanBlockCache(){
                editForm.submit($('edit_form').action+'clean/edit/');
            }
        ";
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('advancedcache_block')->getBlockId()) {
            return Mage::helper('advancedcache')->__("Edit Block '%s'", $this->htmlEscape(Mage::registry('advancedcache_block')->getIdentifier()));
        }
        else {
            return Mage::helper('advancedcache')->__('New Block');
        }
    }

    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        if ($this->hasFormActionUrl()) {
            return $this->getData('form_action_url');
        }
        return $this->getUrl('*/' . $this->_actionsController . '/save');
    }    
}