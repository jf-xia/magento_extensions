<?php

class Topbuy_Searchseo_Adminhtml_SearchseobackendController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->_title($this->__("Searchseo"));
        $this->renderLayout();
    }

}