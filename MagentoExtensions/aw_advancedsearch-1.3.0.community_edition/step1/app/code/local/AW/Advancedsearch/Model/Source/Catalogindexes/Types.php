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

class AW_Advancedsearch_Model_Source_Catalogindexes_Types extends AW_Advancedsearch_Model_Source_Abstract
{
    const CATALOG = 1;
    const CMS_PAGES = 2;
    const AW_BLOG = 3;
    const AW_KBASE = 4;

    const CATALOG_LABEL = 'Catalog';
    const CMS_PAGES_LABEL = 'CMS Pages';
    const AW_BLOG_LABEL = 'AW Blog';
    const AW_KBASE_LABEL = 'AW KBase';

    protected function _toOptionArray()
    {
        $helper = $this->_getHelper();
        $result = array(
            array('value' => self::CATALOG, 'label' => $helper->__(self::CATALOG_LABEL)),
            array('value' => self::CMS_PAGES, 'label' => $helper->__(self::CMS_PAGES_LABEL))
        );
        if ($helper->canUseAWBlog()) {
            $result[] = array('value' => self::AW_BLOG, 'label' => $helper->__(self::AW_BLOG_LABEL));
        }
        if ($helper->canUseAWKBase()) {
            $result[] = array('value' => self::AW_KBASE, 'label' => $helper->__(self::AW_KBASE_LABEL));
        }
        return $result;
    }
}
