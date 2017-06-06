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

class AW_Advancedsearch_Block_Widget_Grid_Column_Renderer_State extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text {
    public function render(Varien_Object $row) {
        $data = $row->getData($this->getColumn()->getIndex());
        $sourceModel = Mage::getModel('awadvancedsearch/source_catalogindexes_state');
        $formatString = '<span class="%s"><span>%s</span></span>';
        switch($data) {
            case AW_Advancedsearch_Model_Source_Catalogindexes_State::READY:
                $cssClass = 'grid-severity-notice';
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_State::REINDEX_REQUIRED:
                $cssClass = 'grid-severity-critical';
                break;
            case AW_Advancedsearch_Model_Source_Catalogindexes_State::DISABLED:
            case AW_Advancedsearch_Model_Source_Catalogindexes_State::NOT_INDEXED:
            default:
                $cssClass = 'grid-severity-major';
        }
        return sprintf($formatString, $cssClass, $sourceModel->getOptionLabel($data));
    }
}
