<?php
class Monk_Blog_RssController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		if (Mage::getStoreConfig('blog/blog/rss'))
		{
			$this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
			$this->loadLayout(false);
			$this->renderLayout();
		}
		else
		{
			$this->_forward('NoRoute');
		}
    }
	
	public function noRouteAction($coreRoute = null)
    {
        $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
        $this->getResponse()->setHeader('Status','404 File not found');

        $pageId = Mage::getStoreConfig('web/default/cms_no_route');
        if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
            $this->_forward('defaultNoRoute');
        }
    }
}