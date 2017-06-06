<?php
class WebspeaksBasket_Basket_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
    }

    public function getcartAction()
    {
		echo $this->getLayout()->createBlock('basket/basket')->setTemplate('basket/basket.phtml')->toHtml();  
    }

    public function removeitemAction()
    {
		if(Mage::getSingleton('customer/session')->isLoggedIn())
		{
			$productId = stripslashes(trim(Mage::app()->getRequest()->getParam('id')));
			
			if(isset($productId) && $productId!='')
			{
				$cartHelper = Mage::helper('checkout/cart');
				$items = $cartHelper->getCart()->getItems();
				foreach ($items as $item)
				{
					if ($item->getProduct()->getId() == $productId)
					{
						$itemId = $item->getItemId();
						$cartHelper->getCart()->removeItem($itemId)->save();
						echo "Item removed successfully.";
						break;
					}
				} 
			}
		}
    }

    public function updatetotalAction()
    {
		echo Mage::helper('checkout')->formatPrice(Mage::helper('checkout/cart')->getQuote()->getGrandTotal());
    }
}