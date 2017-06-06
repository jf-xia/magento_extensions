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

class AW_Advancedsearch_Block_Result_Awblog extends AW_Advancedsearch_Block_Result_Abstract
{
    const PAGER_ID = 'blog_posts_pager';

    public function getPostShortContent($post)
    {
        return $this->_getHelper()->getBlogAPI()->getPostShortContent($post, Mage::app()->getStore()->getId());
    }

    public function getPostUrl($postId)
    {
        return $this->_getHelper()->getBlogAPI()->getPostUrl($postId);
    }

    public function getPager()
    {
        $pager = $this->getChild(self::PAGER_ID);
        if (!$pager->getCollection()) {
            $pager->setCollection($this->getResults());
        }
        return $pager->toHtml();
    }
}
