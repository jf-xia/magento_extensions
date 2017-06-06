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

class AW_Advancedsearch_Model_Source_Catalogindexes_State extends AW_Advancedsearch_Model_Source_Abstract
{
    const DISABLED = 0;
    const NOT_INDEXED = 1;
    const REINDEX_REQUIRED = 2;
    const READY = 3;

    const DISABLED_LABEL = 'Disabled';
    const NOT_INDEXED_LABEL = 'Not Indexed';
    const REINDEX_REQUIRED_LABEL = 'Reindex Required';
    const READY_LABEL = 'Ready';

    protected function _toOptionArray()
    {
        $helper = $this->_getHelper();
        return array(array('value' => self::DISABLED, 'label' => $helper->__(self::DISABLED_LABEL)),
                     array('value' => self::NOT_INDEXED, 'label' => $helper->__(self::NOT_INDEXED_LABEL)),
                     array('value' => self::REINDEX_REQUIRED, 'label' => $helper->__(self::REINDEX_REQUIRED_LABEL)),
                     array('value' => self::READY, 'label' => $helper->__(self::READY_LABEL)));
    }
}
