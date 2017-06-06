<?php

class ZetaPrints_Ordercomments_Model_Comment
  extends Mage_Core_Model_Abstract
{
  /**
   * Order instance
   *
   * @var Mage_Sales_Model_Order
   */
  protected $_order;

  /**
   * @var Mage_Sales_Model_Order_Status_History
   */
  protected $_history;

  /**
   * Initialize resource model
   */
  protected function _construct()
  {
    $this->_init('ordercomments/comment');
  }

  /**
   * Set order object from id
   * @throws RuntimeException
   * @param  int $order_id
   * @return ZetaPrints_Ordercomments_Model_Comment
   */
  public function setOrderFromId($order_id)
  {
    $order = Mage::getModel('sales/order')->load($order_id);
    if ($order->getId()) {
      $this->_order = $order;
    } else {
      throw new RuntimeException('Invalid order id.'); // if id is wrong, throw exception
    }
    return $this;
  }

  /**
   * @return Mage_Sales_Model_Order
   */
  public function getOrder()
  {
    return $this->_order;
  }

  /**
   * Set order object
   *
   * @param   Mage_Sales_Model_Order $order
   * @return  ZetaPrints_Ordercomments_Model_Comment
   */
  public function setOrder(Mage_Sales_Model_Order $order)
  {
    $this->_order = $order;
    return $this;
  }

  /**
   * Add comment to order
   *
   * $order should be either order id or order object
   *
   * @param int|Mage_Sales_Model_Order $order
   * @param int $customer_id
   * @return Mage_Sales_Model_Order_Status_History
   */
  public function addComment($order, $comment, $customer_id = NULL)
  {
    if ($order instanceof Mage_Sales_Model_Order) { // if this is order object save it
      $this->setOrder($order);
    } elseif (is_numeric($order)) { // if order id is passed get actual order
      $this->setOrderFromId($order);
      $order = $this->getOrder();
    } else {
      Mage::log(gettype($order));
      throw new InvalidArgumentException('Order should be either order id or order object.');
    }

    /**
     * @var Mage_Sales_Model_Order_Status_History $history
     */
    $history = $order->addStatusHistoryComment($comment);

    /*
     * If customer is posting this, then he is aware of it.
     * And comment should be visible on front of course.
     */
    $history->setIsCustomerNotified(TRUE)
          ->setIsVisibleOnFront(TRUE)
          ->save();

    $this->_history = $history;
    /*
     * If customer ID is passed, save it to table,
     * we might use this eventually somewhere. Like
     * to indicate which comments are from clients and which
     * from employees.
     */
    if ($customer_id) {
      $comment_id = $history->getId();
      $this->setCommentId($comment_id);
      $this->setCustomerId($customer_id);
      $this->save();
      $comment = $history->getData('comment');

      $this->notifyAdmin($order, $comment);
    }

    return $history;
  }

  /**
   * Send email to admin
   *
   * Send email to admin when customer posts a comment.
   *
   * @param  string $comment
   * @param Mage_Sales_Model_Order $order
   * @return ZetaPrints_Ordercomments_Model_Comment
   */
  private function notifyAdmin(Mage_Sales_Model_Order $order, $comment)
  {
    $storeId = $order->getStore()->getId();

    // Get the destination email addresses to send copies to
    $copyTo = $this->_getEmails(Mage_Sales_Model_Order::XML_PATH_EMAIL_COPY_TO, $storeId);
    $copyMethod = Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_UPDATE_EMAIL_COPY_METHOD, $storeId);
    $from = $order->getCustomerEmail();
    $toIdentity = Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_UPDATE_EMAIL_IDENTITY, $storeId);
    $to = Mage::getStoreConfig('trans_email/ident_' . $toIdentity . '/email', $storeId);
    $admin = Mage::getStoreConfig('trans_email/ident_' . $toIdentity . '/name', $storeId);
    $orderUrl = Mage::getModel('adminhtml/url')->getUrl('adminhtml/sales_order/view', array('order_id' => $order->getId()));

    // Retrieve corresponding email template id and customer name
    $templateId = Mage::getConfig()->getNode('global/template/email/customer_order_comment')->getName();
    $customerName = $order->getCustomerName();

    /**
     * @var Mage_Core_Model_Email_Template_Mailer $mailer
     */
    $mailer = Mage::getModel('core/email_template_mailer');
    /**
     * @var Mage_Core_Model_Email_Info $emailInfo
     */
    $emailInfo = Mage::getModel('core/email_info');
    $emailInfo->addTo($to, $admin);
    if ($copyTo && $copyMethod == 'bcc') {
      // Add bcc to customer email
      foreach ($copyTo as $email) {
        $emailInfo->addBcc($email);
      }
    }
    $mailer->addEmailInfo($emailInfo);

    // Set all required params and send emails
    $mailer->setSender(array('email' => $from, 'name' => $customerName));
    $mailer->setStoreId($storeId);
    $mailer->setTemplateId($templateId);
    $mailer->setTemplateParams(array(
                                    'order'   => $order,
                                    'comment' => $comment,
                                    'billing' => $order->getBillingAddress(),
                                    'orderUrl'=> $orderUrl
                               )
    );
    $mailer->send();

    return $this;
  }

  protected function _getEmails($configPath, $storeId)
  {
    $data = Mage::getStoreConfig($configPath, $storeId);
    if (!empty($data)) {
      return explode(',', $data);
    }
    return false;
  }
}
