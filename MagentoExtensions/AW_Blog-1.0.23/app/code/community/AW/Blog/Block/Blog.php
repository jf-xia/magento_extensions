<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-L.txt
 *
 * @category   AW
 * @package    AW_Blog
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */
class AW_Blog_Block_Blog extends Mage_Core_Block_Template {

    public function getPosts() {

        $tag = $this->getRequest()->getParam('tag');

        $collection = Mage::getModel('blog/blog')->getCollection()
                        ->addPresentFilter()
                        ->addStoreFilter(Mage::app()->getStore()->getId())
                        ->setOrder('created_time ', 'desc');

        if ($tag) {
            $collection->addTagFilter(urldecode($tag));
        }

        $page = $this->getRequest()->getParam('page');


        Mage::getSingleton('blog/status')->addEnabledFilterToCollection($collection);

        $collection->setPageSize((int) Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_PERPAGE));
        $collection->setCurPage($page);

        $route = Mage::helper('blog')->getRoute();


        foreach ($collection as $item) {
            $item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));

            $item->setCreatedTime($this->formatTime($item->getCreatedTime(), Mage::getStoreConfig('blog/blog/dateformat'), true));
            $item->setUpdateTime($this->formatTime($item->getUpdateTime(), Mage::getStoreConfig('blog/blog/dateformat'), true));

            if (Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_USESHORTCONTENT) && trim($item->getShortContent())) {
                $content = trim($item->getShortContent());
                $content = $this->closetags($content);
                $content .= ' <a href="' . $this->getUrl($route . "/" . $item->getIdentifier()) . '" >' . $this->__('Read More') . '</a>';
                $item->setPostContent($content);
            } elseif ((int) Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_READMORE) != 0) {
                $content = $item->getPostContent();
                if (strlen($content) >= (int) Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_READMORE)) {
                    $content = substr($content, 0, (int) Mage::getStoreConfig(AW_Blog_Helper_Config::XML_BLOG_READMORE));
                    $content = substr($content, 0, strrpos($content, ' '));
                    $content = $this->closetags($content);
                    $content .= ' <a href="' . $this->getUrl($route . "/" . $item->getIdentifier()) . '" >' . $this->__('Read More') . '</a>';
                }
                $item->setPostContent($content);
            }


            $comments = Mage::getModel('blog/comment')->getCollection()
                            ->addPostFilter($item->getPostId())
                            ->addApproveFilter(2);
            $item->setCommentCount(count($comments));

            $cats = Mage::getModel('blog/cat')->getCollection()
                            ->addPostFilter($item->getPostId());
            $catUrls = array();
            foreach ($cats as $cat) {
                $catUrls[$cat->getTitle()] = Mage::getUrl($route . "/cat/" . $cat->getIdentifier());
            }
            $item->setCats($catUrls);
        }
        return $collection;
    }

    public function getBookmarkHtml($post) {
        if (Mage::getStoreConfig('blog/blog/bookmarkslist')) {
            $this->setTemplate('aw_blog/bookmark.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getTagsHtml($post) {

        if (trim($post->getTags())) {
            $this->setTemplate('aw_blog/line_tags.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getCommentsEnabled() {
        return Mage::getStoreConfig('blog/comments/enabled');
    }

    public function getPages() {
        
        if ((int) Mage::getStoreConfig('blog/blog/perpage') != 0) {
            $collection = Mage::getModel('blog/blog')->getCollection()
                            ->addPresentFilter()
                            ->addStoreFilter()
                            ->setOrder('created_time ', 'desc');

            /* Incorrect posts per page in tag search mode #7440 Tag search issue
             * 1. Add tag filter to collection
             * 2. Add tag identifier to urls as $tagFilter
             * If not in tags mode - empty string is added
             */
            $tagFilter = '';
            if ($tag = $this->getRequest()->getParam('tag')) {
                $collection->addTagFilter(urldecode($tag));
                $tagFilter = "/tag/{$tag}/";
            }
            /* */

            Mage::getSingleton('blog/status')->addEnabledFilterToCollection($collection);
            $currentPage = (int) $this->getRequest()->getParam('page');
            if (!$currentPage) {
                $currentPage = 1;
            }
            $pages = ceil(count($collection) / (int) Mage::getStoreConfig('blog/blog/perpage'));
            $links = "";
            $route = Mage::helper('blog')->getRoute();
            if ($currentPage > 1) {
                $links = $links . '<div class="left"><a href="' . $this->getUrl($route . '/page/' . ($currentPage - 1) . $tagFilter) . '" >&lt; ' . $this->__('Newer Posts') . '</a></div>';
            }
            if ($currentPage < $pages) {
                $links = $links . '<div class="right"><a href="' . $this->getUrl($route . '/page/' . ($currentPage + 1) . $tagFilter) . '" >' . $this->__('Older Posts') . ' &gt;</a></div>';
            }
            echo $links;
        }
    }

    public function getRecent() {
        if (Mage::getStoreConfig(AW_Blog_Helper_Config::XML_RECENT_SIZE) != 0) {
            $collection = Mage::getModel('blog/blog')->getCollection()
                            ->addPresentFilter()
                            ->addStoreFilter(Mage::app()->getStore()->getId())
                            ->setOrder('created_time ', 'desc');

            $route = Mage::helper('blog')->getRoute();

            Mage::getSingleton('blog/status')->addEnabledFilterToCollection($collection);
            $collection->setPageSize(Mage::getStoreConfig(AW_Blog_Helper_Config::XML_RECENT_SIZE));
            $collection->setCurPage(1);
            foreach ($collection as $item) {
                $item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));
            }
            return $collection;
        } else {
            return false;
        }
    }

    public function getCategories() {
        $collection = Mage::getModel('blog/cat')->getCollection()
                        ->addStoreFilter(Mage::app()->getStore()->getId())
                        ->setOrder('sort_order ', 'asc');

        $route = Mage::helper('blog')->getRoute();

        foreach ($collection as $item) {
            $item->setAddress($this->getUrl($route . "/cat/" . $item->getIdentifier()));
        }
        return $collection;
    }

    public function addTopLink() {
        if (Mage::helper('blog')->getEnabled()) {
            $route = Mage::helper('blog')->getRoute();
            $title = Mage::getStoreConfig('blog/blog/title');
            $this->getParentBlock()->addLink($title, $route, $title, true, array(), 15, null, 'class="top-link-blog"');
        }
    }

    public function addFooterLink() {
        if (Mage::helper('blog')->getEnabled()) {
            $route = Mage::helper('blog')->getRoute();
            $title = Mage::getStoreConfig('blog/blog/title');
            $this->getParentBlock()->addLink($title, $route, $title, true);
        }
    }

    public function closetags($html) {
        return Mage::helper('blog/post')->closetags($html);
    }

    protected function _prepareLayout() {


        $route = Mage::helper('blog')->getRoute();
        $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'blog';

        // show breadcrumbs
        if ($isBlogPage && Mage::getStoreConfig('blog/blog/blogcrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('blog')->__('Home'), 'title' => Mage::helper('blog')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            ;
            if ($tag = @urldecode($this->getRequest()->getParam('tag'))) {
                $breadcrumbs->addCrumb('blog', array('label' => Mage::getStoreConfig('blog/blog/title'), 'title' => Mage::helper('blog')->__('Return to ' . Mage::getStoreConfig('blog/blog/title')), 'link' => Mage::getUrl($route)));
                $breadcrumbs->addCrumb('blog_tag', array('label' => Mage::helper('blog')->__('Tagged with "%s"', Mage::helper('blog')->convertSlashes($tag)), 'title' => Mage::helper('blog')->__('Tagged with "%s"', $tag)));
            } else {
                $breadcrumbs->addCrumb('blog', array('label' => Mage::getStoreConfig('blog/blog/title'), 'title' => Mage::helper('blog')->__('Return to ' . Mage::getStoreConfig('blog/blog/title')), 'link' => Mage::getUrl($route)));
            }
        }
    }

    public function _toHtml() {
        if (Mage::helper('blog')->getEnabled()) {
            $isLeft = ($this->getParentBlock() === $this->getLayout()->getBlock('left'));
            $isRight = ($this->getParentBlock() === $this->getLayout()->getBlock('right'));

            $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'blog';

            $leftAllowed = ($isBlogPage && Mage::getStoreConfig('blog/menu/left') == 2) || (Mage::getStoreConfig('blog/menu/left') == 1);
            $rightAllowed = ($isBlogPage && Mage::getStoreConfig('blog/menu/right') == 2) || (Mage::getStoreConfig('blog/menu/right') == 1);

            if (!$leftAllowed && $isLeft) {
                return '';
            }
            if (!$rightAllowed && $isRight) {
                return '';
            }
            try {
                if (Mage::getModel('widget/template_filter'))
                    $processor = Mage::getModel('widget/template_filter');
                return $processor->filter(parent::_toHtml());
            } catch (Exception $ex) {
                return parent::_toHtml();
            }
        }
    }

}
