<?php

class Topbuy_Homepage_Model_Frontbanner extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("homepage/frontbanner");

    }

    public function getFrontBanner() {
        $frontbanner = $this->getCollection();
        $frontbanner->getSelect()->where('displayfrom<=?', Mage::getModel('core/date')->date())
                ->where('displayfrom>=?', date("Y-m-d", strtotime(Mage::getModel('core/date')->date()) - 6 * 24 * 3600))
                ->where('imageurl<>?', NULL)->where('linkurl<>?', NULL)->where('positiontype<?', '100')
                ->order(array('displayfrom ASC', 'positiontype ASC'))->limit(10);
//Mage::log(print_r($this->getData()));
        $topBanner = array();
        $loadFromSSL = $_SERVER['SERVER_PORT']==443?true:false;
        $topBannerCandidate = array(1 => array(), 2 => array(), 3 => array(), 4 => array(), 5 => array(), 6 => array(), 7 => array(), 8 => array(), 9 => array(), 10 => array());
        foreach ($frontbanner->getItems() as $_item) {
//            Mage::log($_item->getPositiontype()); 
//            Mage::log($_item->getDisplaytitle()); 
            if ($loadFromSSL){
                $topBannerCandidate[$_item->getPositiontype()] = array(0 => $_item->getLinkurl(), 1 => str_replace("http:","https:",$_item->getImageurl()), 2 => $_item->getDisplaytitle());
            } else {
                $topBannerCandidate[$_item->getPositiontype()] = array(0 => $_item->getLinkurl(), 1 => str_replace("https:","http:",$_item->getImageurl()), 2 => $_item->getDisplaytitle());
            }
                
        }
        for ($i = 1; $i < 11; $i++) {
            if ($topBannerCandidate[$i] == null && $i % 2 == 0)
                $topBannerCandidate[$i] = $topBannerCandidate[$i - 1];
            if ($topBannerCandidate[$i] == null && $i % 2 != 0)
                $topBannerCandidate[$i] = $topBannerCandidate[$i + 1];
        }
        $topBanner = $topBannerCandidate;
//        Mage::log($topBannerCandidate); 
        if ((date("h")) % 2 != 0) {
            foreach ($topBanner as $position => $_item) {
                if ($position % 2 == 0) {
                    if (count($topBanner) < 6)
                        break;
                    if(isset($topBanner[$position]))
                    unset($topBanner[$position]);
                }
            }
        } else {
            foreach ($topBanner as $position => $_item) {
                if ($position % 2 != 0) {
                    if (count($topBanner) < 6)
                        break;
                    if(isset($topBanner[$position]))
                    unset($topBanner[$position]);
                }
            }
        }
        return $topBanner;
    }
}
	 