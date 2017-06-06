<?php
class MagicNav_MagiCat_Block_Navigation extends Mage_Catalog_Block_Navigation
{
	protected $_storeCategories;
    public function getCacheKey()
    {
        $key = parent::getCacheKey();
        $customerGroupId = $this->_getCustomerGroupId();
		$productId = Mage::registry('current_product') ? Mage::registry('current_product') : 0;
		$cmsPageId = Mage::app()->getRequest()->getParam('page_id', Mage::getStoreConfig(Mage_Cms_Helper_Page::XML_PATH_HOME_PAGE));

        return 'MAGICAT_' . $key . '_' . $customerGroupId . '_' . $productId . '_' . $cmsPageId;
    }
    protected function _checkLoginCatalog()
    {
    	return $this->_isLoginCatalogInstalledAndActive() && $this->_loginCatalogHideCategories();
    }
    protected function _isLoginCatalogInstalledAndActive()
    {
    	if ($node = Mage::getConfig()->getNode('modules/magiclabs'))
    	{
    		return strval($node->active) == 'true';
    	}
    	return false;
    }
    public function drawItem($category, $level=0, $last=false)
    {
        if ($this->_checkLoginCatalog()) return '';
        return parent::drawItem($category, $level, $last);
    }
    public function drawOpenCategoryItem($category, $level=0, array $levelClass=null)
    {
        $html = array();

        if ($this->_checkLoginCatalog()) return '';
        if (! $category->getIsActive()) return '';

        if (! isset($levelClass)) $levelClass = array();
		$combineClasses = array();

        $combineClasses[] = 'level' . $level;
        if ($this->_isCurrentCategory($category))
        {
        	$combineClasses[] = 'active';
        }
        else
        {
			$combineClasses[] = $this->isCategoryActive($category) ? 'parent' : 'inactive';
        }
		$levelClass[] = implode('-', $combineClasses);

		$levelClass = array_merge($levelClass, $combineClasses);
		
		$levelClass[] =  $this->_getClassNameFromCategoryName($category);
		
		$productCount = '';
		if ($this->displayProductCount())
		{
			$n = $this->_getProductCount($category);
			$productCount = '<span class="product-count"> (' . $n . ')</span>';
		}

        $html[1] = str_pad ( "", (($level * 2 ) + 4), " " ).'<span class="magicat-cat"><a href="'.$this->getCategoryUrl($category).'"><span>'.$this->htmlEscape($category->getName()).'</span></a>'.$productCount."</span>\n";

		$autoMaxDepth = Mage::getStoreConfig('catalog/magicat/expand_all_max_depth');
		$autoExpand = Mage::getStoreConfig('catalog/magicat/expand_all');
		
        if (in_array($category->getId(), $this->getCurrentCategoryPath())
			|| ($autoExpand && $autoMaxDepth == 0)
			|| ($autoExpand && $autoMaxDepth > $level+1)
		) {
			$children = $this->_getCategoryCollection()
				->addIdFilter($category->getChildren());
			
			$children = $this->toLinearArray($children);

            $hasChildren = $children && ($childrenCount = count($children));
            if ($hasChildren)
            {
            	$children = $this->toLinearArray($children);
                $htmlChildren = '';

                foreach ($children as $i => $child)
                {
                	$class = array();
                	if ($childrenCount == 1)
                	{
                		$class[] = 'only';
                	}
                	else
                	{
	                	if (! $i) $class[] = 'first';
	                	if ($i == $childrenCount-1) $class[] = 'last';
                	}
                	if (isset($children[$i+1]) && $this->isCategoryActive($children[$i+1])) $class[] = 'prev';
                	if (isset($children[$i-1]) && $this->isCategoryActive($children[$i-1])) $class[] = 'next';
                    $htmlChildren.= $this->drawOpenCategoryItem($child, $level+1, $class);
                }

                if (!empty($htmlChildren))
                {
					$levelClass[] = 'open';

                    $html[2] = str_pad ( "", ($level * 2 ) + 2, " " ).'<ul>'."\n"
                            .$htmlChildren."\n".
                            str_pad ( "", ($level * 2 ) + 2, " " ).'</ul>';
                }
            }
        }

        $html[0] = str_pad ( "", ($level * 2 ) + 2, " " ).sprintf('<li class="%s">', implode(" ", $levelClass))."\n";

        $html[3] = "\n".str_pad ( "", ($level * 2 ) + 2, " " ).'</li>'."\n";
		
		ksort($html);
        return implode('', $html);
    }
    public function toLinearArray($collection)
    {
    	$array = array();
    	foreach ($collection as $item) $array[] = $item;
    	return $array;
    }
	protected function _sortCategoryArrayByName($a, $b)
	{
		return strcoll($a->getName(), $b->getName());
	}
    protected function _getClassNameFromCategoryName($category)
    {
    	$name = $category->getName();
    	$name = preg_replace('/-{2,}/', '-', preg_replace('/[^a-z-]/', '-', strtolower($name)));
		while ($name && $name{0} == '-') $name = substr($name, 1);
		while ($name && substr($name, -1) == '-') $name = substr($name, 0, -1);
    	return $name;
    }
	protected function _getCustomerGroupId()
	{
		$session = Mage::getSingleton('customer/session');
		if (! $session->isLoggedIn()) $customerGroupId = Mage_Customer_Model_Group::NOT_LOGGED_IN_ID;
		else $customerGroupId = $session->getCustomerGroupId();
		return $customerGroupId;
	}
	protected function _isCurrentCategory($category)
	{
		return ($cat = $this->getCurrentCategory()) && $cat->getId() == $category->getId();
	}
	protected function _getProductCount($category)
	{
		if (null === ($count = $category->getData('product_count')))
		{
			$count = 0;
			if ($category instanceof Mage_Catalog_Model_Category)
			{
				$count =  $category->getProductCount();
			}
			elseif ($category instanceof Varien_Data_Tree_Node)
			{
				$count = $this->_getProductCountFromTreeNode($category);
			}
		}
		return $count;
	}
	protected function _getProductCountFromTreeNode(Varien_Data_Tree_Node $category)
	{
		return Mage::getSingleton('catalog/category')->setId($category->getId())->getProductCount();
	}
	public function getStoreCategories()
	{
		if (isset($this->_storeCategories))
		{
			return $this->_storeCategories;
		}
		$category = Mage::getModel('catalog/category');

		$parent = false;
		switch (Mage::getStoreConfig('catalog/magicat/magicat_root'))
		{
			case 'current':
				if (Mage::registry('current_category'))
				{
					$parent = Mage::registry('current_category')->getId();
				}
				break;
			case 'siblings':
				if (Mage::registry('current_category'))
				{
					$parent = Mage::registry('current_category')->getParentId();
				}
				break;
			case 'root':
				$parent = Mage::app()->getStore()->getRootCategoryId();
				break;
			default:
				$fromLevel = Mage::getStoreConfig('catalog/magicat/magicat_root');
				if (Mage::registry('current_category') && Mage::registry('current_category')->getLevel() >= $fromLevel)
				{
					$cat = Mage::registry('current_category');
					while ($cat->getLevel() > $fromLevel)
					{
						$cat = $cat->getParentCategory();
					}
					$parent = $cat->getId();
				}
		}
		if ($customId = $this->getCategoryId()) {
			$parent = $customId;
		}
		
		if (! $parent && Mage::getStoreConfig('catalog/magicat/fallback_to_root'))
		{
			$parent = Mage::app()->getStore()->getRootCategoryId();
		}
		if (! $parent || ! $category->checkId($parent))
		{
			return array();
		}
		$storeCategories = $this->_getCategoryCollection()
			->addFieldToFilter('parent_id', $parent);
		
		$this->_storeCategories = $storeCategories;
		return $storeCategories;
	}
	protected function _getCategoryCollection()
	{
		$collection = Mage::getResourceModel('catalog/category_collection');
		$collection->addAttributeToSelect('url_key')
			->addAttributeToSelect('name')
			->addAttributeToSelect('all_children')
			->addAttributeToFilter('is_active', 1)
			->addAttributeToFilter('include_in_menu', 1)
			->setOrder('position', 'ASC')
			->joinUrlRewrite();

		if ($this->displayProductCount())
		{
			$collection->setLoadProductCount(true);
		}
		
		return $collection;
	}
	protected function _addProductCount($collection)
	{
		if ($collection instanceof Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection)
		{
			if ($collection->isLoaded())
			{
				$collection->loadProductCount($collection->getItems());
			}
			else
			{
				$collection->setLoadProductCount(true);
			}
		}
		else
		{
			$this->_getProductCollectionResource()->addCountToCategories($collection);
		}
		return $this;
	}
	protected function _getProductCollectionResource()
	{
		if (null === $this->_productCollection)
		{
			$this->_productCollection = Mage::getResourceModel('catalog/product_collection');
		}
		return $this->_productCollection;
	}
	public function displayProductCount()
	{
		return Mage::getStoreConfigFlag('catalog/magicat/display_product_count');
	}
}
