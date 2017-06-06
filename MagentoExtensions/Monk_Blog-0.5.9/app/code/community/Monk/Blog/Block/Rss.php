<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Rss
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Review form block
 *
 * @category   Mage
 * @package    Mage_Rss
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Monk_Blog_Block_Rss extends Mage_Rss_Block_Abstract
{
    protected function _construct()
    {
        /*
        * setting cache to save the rss for 10 minutes
        */
	    $this->setCacheKey('rss_catalog_category_'
            .$this->getRequest()->getParam('cid').'_'
            .$this->getRequest()->getParam('sid')
        );
        $this->setCacheLifetime(600);
    }

    protected function _toHtml()
    {
		$rssObj = Mage::getModel('rss/rss');

		$route = Mage::getStoreConfig('blog/blog/route');
		if ($route == "")
		{
			$route = "blog";
		}
		$url = $this->getUrl($route);
		$title = Mage::getStoreConfig('blog/blog/title');
		$data = array('title' => $title,
			'description' => $title,
			'link'        => $url,
			'charset'     => 'UTF-8'
			);
				
		if (Mage::getStoreConfig('blog/rss/image') != "")
		{
			$data['image'] = $this->getSkinUrl(Mage::getStoreConfig('blog/rss/image'));
		}
				
		$rssObj->_addHeader($data);
					
		$collection = Mage::getModel('blog/blog')->getCollection()
		->addStoreFilter(Mage::app()->getStore()->getId())
		->setOrder('created_time ', 'desc');
		
		$identifier = $this->getRequest()->getParam('identifier');
		if ($cat_id = Mage::getSingleton('blog/cat')->load($identifier)->getcatId())
		{
			Mage::getSingleton('blog/status')->addCatFilterToCollection($collection, $cat_id);
		}
		Mage::getSingleton('blog/status')->addEnabledFilterToCollection($collection);
		
		$collection->setPageSize((int)Mage::getStoreConfig('blog/rss/posts'));
		$collection->setCurPage(1);

		if ($collection->getSize()>0) {
			foreach ($collection as $post) {
			
				$data = array(
							'title'         => $post->getTitle(),
							'link'          => $this->getUrl($route) . $post->getIdentifier(),
							'description'   => $post->getPostContent(),
							'lastUpdate' 	=> strtotime($post->getCreatedTime()),
							);
							
				$rssObj->_addEntry($data);
			}
		}

		return $rssObj->createRssXml();
    }
}