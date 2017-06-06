<?php

/**
 * Catalog navigation
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Topbuy_Magemenu_Block_Navigation extends Mage_Core_Block_Template {

    protected function _construct() {
        $this->addData(array(
            'cache_lifetime' => false,
            'cache_tags' => array("menuNavigation"),
        ));
    }

    public function menuNavigation() {
        $menu = "";
        $_helper = Mage::helper('catalog/category');
        $_categories = $_helper->getStoreCategories();
        if (count($_categories) > 0) {
            $i = 1;
            foreach ($_categories as $_category) {
                $menu .= "<li class='main" . $i . "'>";
                $menu .= "<a href=" . $_helper->getCategoryUrl($_category) . "><span>";
                $menu .= $_category->getName();//$_category->getPosition().' '.$_category->getName();
                $menu .= "</span></a>";
                $menu .= "<div class='highlight'><a href=" . $_helper->getCategoryUrl($_category) . ">";
                $menu .= $_category->getName();//$_category->getPosition().' '.$_category->getName();
                $menu .= "</a></div><div class='submenu'>";
                $_category = Mage::getModel('catalog/category')->load($_category->getId());
                $_subcategories = $_category->getChildrenCategories();
                if (count($_subcategories) > 0) {
                    $dlCount = 0;
                    $j = 1;
                    $menu .= "<dl>";
                    foreach ($_subcategories as $_subcategory) {
                        $_subcategory = Mage::getModel('catalog/category')->load($_subcategory->getId());
                        $_sub3categories = $_subcategory->getChildrenCategories();
                        $dlCount+=count($_sub3categories);
                        $dlCount+=1;
                        //Mage::log($dlCount);
                        if ($dlCount > 18) {
                            $menu .= "</dl><dl>";
                            $j++;
                            $dlCount = count($_sub3categories);
                        }
                        $menu .= "<dt>";
                        $menu .= "<a href=" . $_helper->getCategoryUrl($_subcategory) . ">";
                        $menu .= $_subcategory->getName();//$_subcategory->getPosition().' '.$_subcategory->getName();
                        $menu .= "</a></dt>";
                        if (count($_sub3categories) > 0) {
//                            $menu3 = array();
                            foreach ($_sub3categories as $_sub3category) {
                                if ($_sub3category->getProductCollection()->getSize() > 0) {
//                                    $menu3[$_sub3category->getName()] = "<dd><a href=" . $_helper->getCategoryUrl($_sub3category) . ">".$_sub3category->getName()."</a></dd>";
                                    $menu .= "<dd>";
                                    $menu .= "<a href=" . $_helper->getCategoryUrl($_sub3category) . ">";
                                    $menu .= $_sub3category->getName();//$_sub3category->getPosition().' '.$_sub3category->getName();
                                    $menu .= "</a>";
                                    $menu .= "</dd>";
                                }
                            }
//                            ksort($menu3);
//                            foreach ($menu3 as $value) {
//                                $menu .= $value;
//                            }
                        }
                    }
                    $menu .= "</dl>";
                    if ($j < 5) {
                        $menu .="<dl><dt class='special'>Special Offer</dt>";
                        $menu .="<dd><a href='" . $_helper->getCategoryUrl($_category) . "?dir=desc&order=created_at' rel='nofollow'>New Arrivals</a></dd>";
                        $menu .="<dd><a href='" . $_helper->getCategoryUrl($_category) . "?dir=desc&order=position' rel='nofollow'>Top Sellers</a></dd>";
                        $menu .="<dd><a href='" . $_helper->getCategoryUrl($_category) . "?dir=desc&order=customerviewed' rel='nofollow'>Most Viewed</a></dd>";
                        $menu .="<dd><a href='" . $_helper->getCategoryUrl($_category) . "?dir=desc&order=productreview' rel='nofollow'>Most Reviewed</a></dd></dl>";
//                        $menu .="<dd><a href=''>Samsung LED / Plasma TV Cash Back Promotion</a></dd>";
//                        $menu .="<dd><a href=''>40% Off HDTop Set Box DVD Player Combined</a></dd>";
                    }
                }
                $menu .= "<div class='clear'></div></div></li>";
                $i++;
            }
        }
        return $menu;
    }

}