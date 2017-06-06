<?php

class Topbuy_Homepage_Block_Topcategores extends Mage_Core_Block_Template {

    protected function _construct()
    {
        $this->addData(array(
            'cache_lifetime'    => "3600",
            'cache_tags'        => array("getTopcategores"),
        ));
    }

    public function getTopcategores() {
        //SELECT  * FROM  `tb_categoryspecial` WHERE  `linkflag` >0 and `linkflag` <3 ORDER BY  `linenumber` ASC 
        $categoryspe = Mage::getModel('homepage/categoryspecial')->getCollection();
        $categoryspe->getSelect()->where('linkflag>?', 0)->where('linkflag<?', 3)->order(array('linenumber ASC', 'sortby ASC'));
        $topCategory = "";
        $i = 1;
//        $loadFromSSL = $_SERVER['SERVER_PORT']==443?true:false;
//        if ($loadFromSSL){
//            $sslUrl = str_replace("http:","https:",$this->getSkinUrl('images/categoryIcon/topCategory/'));
//        } else {
//            $sslUrl = $this->getSkinUrl('images/categoryIcon/topCategory/');
//        }
        foreach ($categoryspe as $_item) {
            $topCategoryUrl = Mage::getModel("catalog/category")->load($_item->getIdparentcategory())->getUrl();
            //Mage::log($topCategoryUrl);
            if ($_item->getLinkflag() == 1){
	            if (strlen($_item->getLinkname()) > 20) {
	                $linkName = substr($_item->getLinkname(), 0, 19) . "...";
	            } else {
	                $linkName = $_item->getLinkname();
	            }            	
            }else{
                if (strlen($_item->getLinkname()) > 29) {
                    $linkName = substr($_item->getLinkname(), 0, 27) . "...";
                } else {
                    $linkName = $_item->getLinkname();
                }
            }
            if ($_item->getLinkflag() == 1 && $_item->getSortby() == 1) {
                $topCategory.= "<li><a href='" . $topCategoryUrl . "' target='_blank'><img src='skin/frontend/topbuy/default/images/categoryIcon/topCategory/". $_item->getLinkstatus() . ".png' alt='".$linkName."' /></a><dl><dt><h3>" . $linkName . "</h3></dt>";
            }
            if ($_item->getLinkflag() == 1 && $_item->getSortby() != 1) {
                $topCategory.= "</dl><div class='clear'></div></li>" . "<li><a href='" . $topCategoryUrl . "' target='_blank'><img src='skin/frontend/topbuy/default/images/categoryIcon/topCategory/" . $_item->getLinkstatus() . ".png' alt='".$linkName."' /></a><dl><dt><h3>" . $linkName . "</h3></dt>";
                $i = 1;
            }
            if ($_item->getLinkflag() == 2 && $i < 5) {
                $topCategory.= "<dd><a href='" . $topCategoryUrl . "' title='" . $linkName . "' target='_blank'>" . $linkName . "</a></dd>";
                $i++;
            } else if ($i == 5) {
                $topCategory.= "<dd class='more'><a href='". Mage::getModel("catalog/category")->load($_item->getIdparentcategory())->getParentCategory()->getUrl() ."' rel='nofollow' target='_blank'>See More Products »</a></dd>";
                $i++;
            }
        }
        $topCategory.= "</dl><div class='clear'></div></li>";
        return $topCategory;
    }

    public function getTopcatMobile() {
        $categoryspe = Mage::getModel('homepage/categoryspecial')->getCollection();
        $categoryspe->getSelect()->where('sortby=?', 0)->order('linenumber ASC');
        $topCategory = "";
        foreach ($categoryspe as $_item) {
            $topCategoryUrl = Mage::getModel("catalog/category")->load($_item->getIdparentcategory())->getUrl();
            $topCategory.= '<li><a href="' . $topCategoryUrl . '"><span>' . $_item->getLinkname() . '</span></a></li>';
        }
        return $topCategory;
    }

}





















//            $i++;
//            $topCategoryArray[$i]= array(0 => $_item->getSortby(), 1 => $_item->getIdparentcategory(), 2 => $_item->getDisplaytitle());

//<li>
//      <a href='' target='_blank'><img src='http://www.topbuy.com.au/tbcart/pc/catalog/homeCateBanner/".$_item->getIdparentcategory().".png'/></a>
//      <dl>
//        <dt>Audio &amp; Video</dt>
//        <dd><a href="" target="_blank">Blu-Ray Players</a></dd>
//        <dd><a href="" target="_blank">GPS Devices</a></dd>
//        <dd><a href="" target="_blank">Home Theatre</a></dd>
//        <dd><a href="" target="_blank">LCD, LED and Plasma TVs</a></dd>
//        <dd class='more'><a href='' target='_blank'>See More Products »</a></dd>
//      </dl>
//      <div class="clear"></div> 
//    </li>

//        $_helper = Mage::helper('catalog/category');
//        $_categories = $_helper->getStoreCategories();
//        $topcategore = "";
////$currentCategory = Mage::registry('current_category');
//        if (count($_categories) > 0) {
//            foreach ($_categories as $_category) {
//                $_category = Mage::getModel('catalog/category')->load($_category->getId());
//                $_subcategories = $_category->getChildrenCategories();
//                if (count($_subcategories) > 0) {
//                    foreach ($_subcategories as $_subcategory) {
//                        $topcategore .= "<li>";
//                        $topcategore .= "<a href='" . $_helper->getCategoryUrl($_subcategory) . "' target='_blank' >";
//                        $topcategore .= "<img src='" . $this->getSkinUrl('images/') . "layout/topcategory_av.png' />"; //$_subcategory->getName();
//                        $topcategore .= "</a><dl><dt>" . $_subcategory->getName() . "</dt>";
//                        $_subcategory = Mage::getModel('catalog/category')->load($_subcategory->getId());
//                        $_sub3categories = $_subcategory->getChildrenCategories();
//                        if (count($_sub3categories) > 0) {
//                            $i = 1;
//                            foreach ($_sub3categories as $_sub3category) {
//                                if ($i>5) break;
//                                $topcategore .= "<dd>";
//                                $topcategore .= "<a href='" . $_helper->getCategoryUrl($_sub3category) . "' target='_blank' >";
//                                $topcategore .= $_sub3category->getName();
//                                $topcategore .= "</a>";
//                                $topcategore .= "</dd>";
//                                $i++;
//                            }
//                        }
//                        $topcategore .= "</li>";
//                    }
//                }
//            }
//        }
//        return $topcategore;