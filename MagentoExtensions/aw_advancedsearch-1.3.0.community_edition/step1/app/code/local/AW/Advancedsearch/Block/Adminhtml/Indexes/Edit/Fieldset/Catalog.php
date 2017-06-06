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

class AW_Advancedsearch_Block_Adminhtml_Indexes_Edit_Fieldset_Catalog extends AW_Advancedsearch_Block_Adminhtml_Indexes_Edit_Fieldset_Abstract
{
    const TYPE = AW_Advancedsearch_Model_Source_Catalogindexes_Types::CATALOG;

    protected function _getIndexAttributes()
    {
        return Mage::getModel('awadvancedsearch/source_catalogindexes_catalog_product_attributes')->toOptionArray();
    }

    protected function _getDataObject()
    {
        $data = Mage::helper('awadvancedsearch/forms')->getFormData($this->getRequest()->getParam('id'));
        if (!is_object($data)) {
            $data = new Varien_Object($data);
        }
        if($data->getData('type') && $data->getData('type') != self::TYPE) {
            $data = new Varien_Object(array());
        }
        return $data;
    }
}
