<?php
class Monk_Blog_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$this->loadLayout();
		
		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('blog/blog/layout'));
		if (Mage::getStoreConfig('blog/blog/blogcrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))){
			$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Go to Home Page'), 'link'=>Mage::getBaseUrl()));;
			$breadcrumbs->addCrumb('blog_blog', array('label'=>Mage::getStoreConfig('blog/blog/title'), 'title'=>Mage::getStoreConfig('blog/blog/title')));
		}
	
		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('blog/blog/title'));
			$head->setKeywords(Mage::getStoreConfig('blog/blog/keywords'));
			$head->setDescription(Mage::getStoreConfig('blog/blog/description'));
			if (Mage::getStoreConfig('blog/rss/enable'))
			{
				$route = Mage::getStoreConfig('blog/blog/route');
				if ($route == "")
				{
					$route =  "blog";
				}
				
				$head->addItem("rss", Mage::getUrl($route) . "rss");
			}
		}
		$this->renderLayout();
    }
}