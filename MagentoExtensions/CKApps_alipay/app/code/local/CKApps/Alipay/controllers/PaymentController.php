<?php
/**
*
* Copyright CKApps.com
* email: app@ckapps.com
*
*/
class CKApps_Alipay_PaymentController extends Mage_Core_Controller_Front_Action
{
	/**
	* Order instance
	*/
	protected $_order;

	/**
	*Direct to the page who's url is $link
	*/
	public function redirect($link)
	{
		$url = $link;
		echo "<script type='text/javascript'>";
		echo "window.location.href='$url'";
		echo "</script>"; ;
	}
	/**
	*  Get order
	*
	*  @param    none
	*  @return	  Mage_Sales_Model_Order
	*/
	public function getOrder()
	{
		if ($this->_order == null)
		{
			$session = Mage::getSingleton('checkout/session');
			$this->_order = Mage::getModel('sales/order');
			$this->_order->loadByIncrementId($session->getLastRealOrderId());
		}
		return $this->_order;
	}


	/**
	* When a customer chooses Alipay on Checkout/Payment page
	*
	*/
	public function redirectAction()
	{
		$session = Mage::getSingleton('checkout/session');
		$session->setAlipayPaymentQuoteId($session->getQuoteId());
		$order = $this->getOrder();
		if (!$order->getId())
		{
			$this->norouteAction();
			return;
		}

		$order->addStatusToHistory(
		$order->getStatus(),
		Mage::helper('alipay')->__('Customer was redirected to Alipay')
		);
		$order->save();
		$this->getResponse()
		->setBody($this->getLayout()
		->createBlock('alipay/redirect')
		->setOrder($order)
		->toHtml());

		$session->unsQuoteId();
	}
	/**
	*Get the information sent by alipay and verify it then do someting
	*/
	public function notifyAction()
	{

		if ($this->getRequest()->isPost())
		{
			$postData = $this->getRequest()->getPost();
			$method = 'post';
		} else if ($this->getRequest()->isGet())
		{
			$postData = $this->getRequest()->getQuery();
			$method = 'get';

		} else
		{
			return;
		}
		$payment=Mage::getModel('alipay/payment');
		$result=$payment->verifyNotify($postData);
		if($result=="true"){
			$trade_no=$postData['trade_no'];
			$out_trade_no=$postData['out_trade_no'];
			$payment->saveTrade_no($out_trade_no,$trade_no);
			$order_status=$payment->transformTrdadeStatus($postData['trade_status']);
			$order = Mage::getModel('sales/order');
			$order->loadByIncrementId($postData['out_trade_no']);
			$order->addStatusToHistory(
			$order_status,
			Mage::helper('alipay')->__($postData['trade_status']));
			try
			{
				$order->save();
			} catch(Exception $e)
			{
				Mage::logException($e);
			}
			echo "success";
		}
		else Mage::log('Unsafe information get from notify!!!');
	}
	/*
	*Save invoice
	*/

	protected function saveInvoice(Mage_Sales_Model_Order $order)
	{
		if ($order->canInvoice())
		{
			$convertor = Mage::getModel('sales/convert_order');
			$invoice = $convertor->toInvoice($order);
			foreach ($order->getAllItems() as $orderItem)
			{
				if (!$orderItem->getQtyToInvoice())
				{
					continue ;
				}
				$item = $convertor->itemToInvoiceItem($orderItem);
				$item->setQty($orderItem->getQtyToInvoice());
				$invoice->addItem($item);
			}
			$invoice->collectTotals();
			$invoice->register()->capture();
			Mage::getModel('core/resource_transaction')
			->addObject($invoice)
			->addObject($invoice->getOrder())
			->save();
			return true;
		}

		return false;
	}

	/**
	*  Success payment page
	*
	*  @param    none
	*  @return	  void
	*/
	public function returnAction()
	{
		if ($this->getRequest()->isPost())
		{
			$getData = $this->getRequest()->getPost();
			$method = 'post';
		} else if ($this->getRequest()->isGet())
		{
			$getData = $this->getRequest()->getQuery();
			$method = 'get';

		} else
		{
			return;
		}
		$session = Mage::getSingleton('checkout/session');
		$session->setQuoteId($session->getAlipayPaymentQuoteId());
		$session->unsAlipayPaymentQuoteId();
		$order = $this->getOrder();
		$verify_return=Mage::getModel('alipay/payment');
		$result=$verify_return->verifyNotify($getData);
		if($result=="true"){
			$trade_no=$getData['trade_no'];
			$out_trade_no=$getData['out_trade_no'];
			$verify_return->saveTrade_no($out_trade_no,$trade_no);
			if (!$order->getId())
			{
				$this->norouteAction();
				return;
			}

			$order->addStatusToHistory(
			$order->getStatus(),
			Mage::helper('alipay')->__('Customer successfully returned from Alipay')
			);
			$order->sendNewOrderEmail();
			$order->save();
			$this->_redirect('checkout/onepage/success');
		}
		else
		{ if (!$order->getId())
			{
				$this->norouteAction();
				return;
			}

			$order->addStatusToHistory(
			$order->getStatus(),
			Mage::helper('alipay')->__('There was an error occurred during paying process')
			);
			$order->save();
			$this->_redirect('checkout/onepage/failure');
			} 
	}

}
