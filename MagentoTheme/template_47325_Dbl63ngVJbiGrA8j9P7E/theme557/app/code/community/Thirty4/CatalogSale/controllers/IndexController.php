<?php

class Thirty4_CatalogSale_IndexController extends Mage_Core_Controller_Front_Action
{
  public function indexAction()
  {
    $this->loadLayout();
    $this->_initLayoutMessages('catalog/session');
    $this->renderLayout();
  }
}
