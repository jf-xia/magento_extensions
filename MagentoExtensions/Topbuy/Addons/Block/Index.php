<?php

class Topbuy_Addons_Block_Index extends Mage_Core_Block_Template {

//    protected function _construct() {
//        $this->addData(array(
//            'cache_lifetime' => "3600",
//            'cache_tags' => array("addons"),
//        ));
//    }

    //catalog/product/view.phtml
    public function getAddons($_product) {
        $_coreHelper = Mage::helper('core');
        $pIdProduct = $_product->getId();
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $csgroup = $read->fetchAll("SELECT tb_csgroup.csgroupname, tb_csgroup.idcsgroup, tb_csproductmap.idproduct, tb_csproductmap.sortby FROM tb_csgroup Inner Join tb_csproductmap ON tb_csproductmap.idcsgroup = tb_csgroup.idcsgroup WHERE tb_csproductmap.idproduct =  ? ORDER BY tb_csproductmap.sortby ASC;", $pIdProduct);
        $addonsHtml='';
        if ($csgroup) {
            $addonsHtml .= '<div id="topbuy-addon"><h2 class="topbuy-prd-blocktitle">Essential addons for this item</h2><ul id="topbuy-addon-grp">';
            foreach ($csgroup as $_item) {
                if ($_item['idproduct'] == $pIdProduct) {
                    $addonsArr = array();
                    $csgroupproduct = Mage::getModel('addons/csgroupproduct')->getCollection()->addFilter("idcsgroup", $_item['idcsgroup']);
//                    $firstproduct = Mage::getModel('catalog/product')->load($csgroupproduct->getFirstItem()->getIdcsproduct());
                    foreach ($csgroupproduct as $_itemp) {
                        $addonsproduct = Mage::getModel('catalog/product')->load($_itemp->getIdcsproduct());
                        $qtyStock = (int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($addonsproduct)->getQty();
                        if($addonsproduct->isSaleable()&&$qtyStock>0) {
                        $addonsArr[$addonsproduct->getPrice()] = array(
                            'id'=>$addonsproduct->getId(),
                            'name'=>$addonsproduct->getName(),
                            'image'=>Mage::helper('catalog/image')->init($addonsproduct, 'small_image')->resize(70),
                            'price'=>$_coreHelper->currency($addonsproduct->getPrice(), true, FALSE)
                            );
                        }
                    }
                    ksort($addonsArr);
                    $firsta = reset($addonsArr);
                    if ($firsta['id']) {
                    $addonsHtml .= '<li data-value="'.$_item['idcsgroup'].'">
                                        <div class="grp-block">
                                        <p class="grp-index"><span></span></p>
                                        <p class="grp-img"><img src="'.$firsta['image'].'" width="40" /></p>
                                        <p class="grp-name">'.$_item['csgroupname'].', <span>from '.$firsta['price'] .'</span></p>
                                        <p class="grp-switch"><a><span>Show More</span></a></p>
                                        </div>
                                        <ul id="topbuy-addon-item" class="topbuy-addon-item">';
//                    foreach ($addonsArr as $_itemas) {
//                        $addonsHtml .='   <li>
//                                            <a id="'.$this->getUrl('ajax/product/index/') . 'pid/'.$_itemas['id'].'" href="javascript:void(0);">
//                                            <div class="item-img"><img src="'.$_itemas['image'].'" width="70" /></div>
//                                            <div class="item-info">
//                                                <h3>'.$_itemas['name'].'</h3>
//                                                <p>'.$_itemas['price'] .'</p>
//                                                <span>+ Add</span>
//                                            </div> 
//                                            </a>
//                                           </li>';
//                    }
                    $addonsHtml .= '    </ul></li>';
                    }
                }
            }
            $addonsHtml .= '</ul></div>';
        }
        return $addonsHtml;
    }

}