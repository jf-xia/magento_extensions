<?php   
class Topbuy_Newsletternotify_Block_Index extends Mage_Core_Block_Template{   

    public function getLast7News() {
        $lastNewsArray=array();
        $last7NewsModel=Mage::getModel('newsletternotify/newsletterheader')->getCollection();
        $last7NewsModel->getSelect()
                ->where('senddate>?',date('Y-m-d h:i:s',strtotime(Mage::getModel('core/date')->date())-(3600*24*7)))
                ->order('senddate DESC')->limit(7);
        foreach($last7NewsModel as $_item){
            $lnArray=array($_item->getSenddate(),$_item->getNewssubject());
            array_push($lastNewsArray, $lnArray);
        }
        return $lastNewsArray;
    }



}