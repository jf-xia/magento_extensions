<?php


class Topbuy_Homepage_Block_Categoryspecial extends Mage_Core_Block_Template
{

    protected function _construct()
    {
        $this->addData(array(
            'cache_lifetime'    => "3600",
            'cache_tags'        => array("getCategoryspecial"),
        ));
    }

    public function getCategoryspecial(){
        $categoryspecial=Mage::getModel('homepage/categoryspecial')->getCategoryspecial();
        $categorysp="";
//        $loadFromSSL = $_SERVER['SERVER_PORT']==443?true:false;
//        if ($loadFromSSL){
//            $sslUrl = str_replace("http:","https:",$this->getSkinUrl('images/categoryIcon/hotSelling/'));
//        } else {
//            $sslUrl = $this->getSkinUrl('images/categoryIcon/hotSelling/');
//        }
        foreach ($categoryspecial as $_item){
            if ($_item->getSortby()==0) $categorysp.= "<dl><dt><img src='skin/frontend/topbuy/default/images/categoryIcon/hotSelling/".$_item->getIdparentcategory().".png' alt='".$_item->getLinkname()."' /></dt>";
            $categorysp.=  "<dd><a href='".$_item->getLinkhref()."' title='" . $_item->getLinkname() . "' target='_blank'>".$_item->getLinkname()."</a></dd>";
            if ($_item->getSortby()==4) $categorysp.= "</dl><div class='clear'></div>";
        }
        return $categorysp;
    }

}