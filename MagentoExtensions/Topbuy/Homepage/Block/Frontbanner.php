<?php

class Topbuy_Homepage_Block_Frontbanner extends Mage_Core_Block_Template {


//    public function _construct()
//    {
//        parent::_construct();
//        $this->addData(array(
//            'cache_lifetime'    => "3600",
//            'cache_tags'        => array("getFrontBanner"),
//        ));
//    }

    public function getFrontBanner() {
        $topBanner = Mage::getModel('homepage/frontbanner')->getFrontBanner();
        return $topBanner;
    }
}

