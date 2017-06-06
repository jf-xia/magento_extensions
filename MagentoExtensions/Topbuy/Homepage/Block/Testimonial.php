<?php

class Topbuy_Homepage_Block_Testimonial extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        $this->addData(array(
            'cache_lifetime'    => "3600",
            'cache_tags'        => array("getTestimonial"),
        ));
    }

    public function getTestimonial(){

        $testimonial=Mage::getModel('homepage/testimonial')->getCollection();
        $testimonial->getSelect()->where('idstore=?', '99')->order('senddate DESC')->limit(10); 
        $testimoni="";
        foreach ($testimonial as $_item){
            $testimoni.="<dt><h4>".$_item->getSubject()."</h4>";
            $testimoni.="<h5>".$_item->getFromname()." from ".$_item->getFromstate()." posted on ".$_item->getSenddate()."</h5></dt>";
            $testimoni.="<dd><p>".$_item->getContentbody()."</p></dd>";
        }
        return $testimoni;
    }

    public function getTestimonialView(){

        $testimonial=Mage::getModel('homepage/testimonial')->getCollection();
        $testimonial->getSelect()->where('idstore=?', '99')->order('senddate DESC')->limit(100); 
        $testimonialArray=array();
        foreach ($testimonial as $_item){
            $testimoni = array('subject'=>$_item->getSubject(),
                                'name'=>$_item->getFromname(),
                                'state'=>$_item->getFromstate(),
                                'senddate'=>$_item->getSenddate(),
                                'body'=>$_item->getContentbody());
            array_push($testimonialArray,$testimoni);
        }
        return $testimonialArray;
    }

}
	 