<?php

class Topbuy_Homepage_Model_Categoryspecial extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("homepage/categoryspecial");

    }

    public function getCategoryspecial(){
        $categoryspecial=$this->getCollection();
        $categoryspecial->getSelect()->where('linkflag=?', 0)->order(array('linenumber ASC', 'sortby ASC')); 
        return $categoryspecial;
    }

}
	 