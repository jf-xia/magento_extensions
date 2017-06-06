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

class AW_Advancedsearch_Block_Adminhtml_Indexes_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_indexes';
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'awadvancedsearch';
        
        if(Mage::helper('awadvancedsearch')->isEditAllowed()) {
            $this->_addButton('saveandreindex', array(
                'label' => $this->__('Save And Reindex'),
                'onclick' => 'awasSavenReindex()',
                'class' => 'save',
                'id' => 'awas-save-and-reindex'
            ), -200);
        } else {
            $this->_removeButton('save');
            $this->_removeButton('delete');
        }
        
        $this->_formScripts[] = "awas_indexes.setRequestUrl('".$this->getUrl('*/*/typeform')."');"
                              . "awas_indexes.indexId = '".$this->getRequest()->getParam('id')."';"
                              . "function awasSavenReindex() {"
                              . "   if($('edit_form').action.indexOf('reindex/1/')<0)"
                              . "       $('edit_form').action += 'reindex/1/';"
                              . "editForm.submit();}";
    }

    public function getHeaderText()
    {
        return $this->__('Manage Catalog Index');
    }
}
