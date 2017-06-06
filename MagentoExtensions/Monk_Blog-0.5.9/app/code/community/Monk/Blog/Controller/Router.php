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
 * @package    Mage_Cms
 * @copyright  Copyright (c) 2004-2007 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Monk_Blog_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();

        $blog = new Monk_Blog_Controller_Router();
        $front->addRouter('blog', $blog);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
		
		$route = Mage::getStoreConfig('blog/blog/route');
		if ($route == "")
		{
			$route = "blog";
		}
		
		$identifier = $request->getPathInfo();
		

		if (substr(str_replace("/", "",$identifier), 0, strlen($route)) != $route)
		{
			return false;
		}
		
        $identifier = substr_replace($request->getPathInfo(),'', 0, strlen("/" . $route. "/") );
		$identifier = str_replace('.html', '', $identifier);
		if($identifier == '')
		{
			$request->setModuleName('blog')
				->setControllerName('index')
				->setActionName('index');
				return true;
		}
		
		if (strpos($identifier, '/'))
		{
			$page = substr($identifier, strpos($identifier, '/') + 1);
		}
		
		if (substr($identifier, 0, strlen('cat/')) == 'cat/')
		{
			$identifier = substr_replace($identifier,'', 0, strlen('cat/'));

			if (strpos($identifier, '/page/'))
			{
				$page = substr($identifier, strpos($identifier, '/page/') + 6);
				$identifier = substr_replace($identifier,'', strpos($identifier, '/page/'), strlen($page)+6);
			}
			
			if (strpos($identifier, '/post/'))
			{
				$postident = substr($identifier, strpos($identifier, '/post/') + 6);
				$identifier = substr_replace($identifier,'', strpos($identifier, '/post/'), strlen($postident)+6);
				$postident = str_replace('/', '', $postident);
			}
			
			$rss = false;
			if (strpos($identifier, '/rss'))
			{
				$rss = true;
				$identifier = substr_replace($identifier,'', strpos($identifier, '/rss'), strlen($page)+4);
			}
			$identifier = str_replace('/', '', $identifier);
			
			$cat = Mage::getSingleton('blog/cat');
			if (!$cat->load($identifier)->getCatId()) {
				return false;
			}
			
			if ($rss)
			{
				$request->setModuleName('blog')
					->setControllerName('rss')
					->setActionName('index')
					->setParam('identifier', $identifier);
			}
			else if (isset($postident))
			{
				$post = Mage::getSingleton('blog/post');
				if (!$post->load($postident)->getId()) {
					return false;
				}
		
				$request->setModuleName('blog')
					->setControllerName('post')
					->setActionName('view')
					->setParam('identifier', $postident)
					->setParam('cat', $identifier);
				return true;
			}
			else
			{
				$request->setModuleName('blog')
					->setControllerName('cat')
					->setActionName('view')
					->setParam('identifier', $identifier);
				if (isset($page))
				{
					$request->setParam('page', $page);
				}
			}
			return true;
		}
		else if (substr($identifier, 0, strlen('page/')) == 'page/')
		{
			$identifier = substr_replace($identifier,'', 0, strlen('page/'));
			
			$request->setModuleName('blog')
				->setControllerName('index')
				->setActionName('index');
			if (isset($page))
			{
				$request->setParam('page', $page);
			}
			return true;
		}
		else if (substr($identifier, 0, strlen('rss')) == 'rss')
		{
			$identifier = substr_replace($identifier,'', 0, strlen('rss/'));
			
			$request->setModuleName('blog')
				->setControllerName('rss')
				->setActionName('index');
			return true;
		}
		else
		{	
			$identifier = str_replace('/', '', $identifier);
			$post = Mage::getSingleton('blog/post');
			if (!$post->load($identifier)->getId()) {
				return false;
			}

			$request->setModuleName('blog')
				->setControllerName('post')
				->setActionName('view')
				->setParam('identifier', $identifier);
			if (isset($page))
			{
				$request->setParam('page', $page);
			}
			return true;
		}
		return false;
    }
}