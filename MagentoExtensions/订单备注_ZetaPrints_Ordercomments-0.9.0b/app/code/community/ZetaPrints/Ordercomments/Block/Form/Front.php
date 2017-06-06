<?php
class ZetaPrints_Ordercomments_Block_Form_Front
  extends Mage_Adminhtml_Block_Sales_Order_View_History
{
  protected function _construct()
  {
    parent::_construct();
    $this->setTemplate('ordercomments/form/front.phtml');
  }

  protected function _prepareLayout()
  {
    parent::_prepareLayout();
    $this->unsetChild('submit_button');
    return $this;
  }

  public function getOrderHistoryHtml()
  {
    $html = '<div class="order-additional order-comments"><h2 class="sub-title">'. $this->__('About Your Order') . '</h2>';
    $html .= '<dl class="order-about"></dl></div>';
    return $html;
  }

  /**
   * @return Mage_Sales_Model_Order
   */
  public function getOrder()
  {
    return Mage::registry('current_order');
  }

  public function canAddComment()
  {
    return $this->getOrder()->canComment();
  }
}
