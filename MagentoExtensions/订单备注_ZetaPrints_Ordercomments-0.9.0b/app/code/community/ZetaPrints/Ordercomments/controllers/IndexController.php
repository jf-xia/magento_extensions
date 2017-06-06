<?php
class ZetaPrints_Ordercomments_IndexController
  extends Mage_Core_Controller_Front_Action
{
  protected function _construct()
  {
    $this->setFlag('', 'no-renderLayout', TRUE);
  }

  public function addCommentAction()
  {
    /**
     * @var ZetaPrints_Ordercomments_Model_Comment $comment
     */
    $comment = Mage::getModel('ordercomments/comment');
    $customerSession = Mage::getSingleton('customer/session');
    $customer_id = $customerSession->getCustomerId();
    $order_id = $this->getRequest()->getParam('order_id');
    $text = $this->getRequest()->getParam('comment');
    try{
      $history = $comment->addComment($order_id, $text, $customer_id);
      $data = array(
        'comment' => $history->getData('comment'),
        'created' => Mage::helper('core')->formatDate($history->getCreatedAt(), 'medium', true) // format date the way it is shown on order page
      );
      $this->getResponse()->setHeader('Content-type', 'application/x-json');
      $this->getResponse()->setBody(Zend_Json::encode($data));
    }catch(Exception $e){
      return $e->getMessage();
    }
  }
}
