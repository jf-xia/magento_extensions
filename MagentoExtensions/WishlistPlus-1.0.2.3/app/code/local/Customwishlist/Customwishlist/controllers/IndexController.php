<?php
class Customwishlist_Customwishlist_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
    }

    /**
     * Add selected items on wishlist page to shoping cart
     *
     */
    public function addselectedtocartAction() {
        $messages           = array();
        $urls               = array();
        $wishlistIds        = array();
        $notSalableNames    = array(); // Out of stock products message

		$ids = Mage::helper('core')->htmlEscape($this->getRequest()->getParam('ids'));
		$qtys = Mage::helper('core')->htmlEscape($this->getRequest()->getParam('qtys'));
		
		$ids_arr = split(',', $ids);
		$qtys_arr = split(',', $qtys);
		
		$i=0;
		foreach($ids_arr as $id)
		{
			$id = intval($id);
			$qty = intval($qtys_arr[$i]);
			if($id != '' && $qty != '')
			{
				try {
					$product = Mage::getModel('catalog/product')
						->load($id)
						->setQty($qty);
					if ($product->isSalable()) {
						Mage::getSingleton('checkout/cart')->addProduct($product,$qty);
					}
					else {
						$notSalableNames[] = $product->getName();
					}
				}
				catch(Exception $e)
				{
					$url = Mage::getSingleton('checkout/session')->getRedirectUrl(true);
					if ($url)
					{
						$url = Mage::getModel('core/url')
							->getUrl('catalog/product/view', array(
								'id'            => $id,
								'wishlist_next' => 1
							));

						$urls[]         = $url;
						$messages[]     = $e->getMessage();
						$wishlistIds[]  = $id;
					} else {
						//$item->delete();
					}
				}
				Mage::getSingleton('checkout/cart')->save();
            }
			$i++;
		}

        if (count($notSalableNames) > 0) {
            Mage::getSingleton('checkout/session')
                ->addNotice($this->__('Following product(s) is currently out of stock:'));
            array_map(array(Mage::getSingleton('checkout/session'), 'addNotice'), $notSalableNames);
        }

        if ($urls) {
            Mage::getSingleton('checkout/session')->addError(array_shift($messages));
            $this->getResponse()->setRedirect(array_shift($urls));

            Mage::getSingleton('checkout/session')->setWishlistPendingUrls($urls);
            Mage::getSingleton('checkout/session')->setWishlistPendingMessages($messages);
            Mage::getSingleton('checkout/session')->setWishlistIds($wishlistIds);
        }
        else {
            Mage::getSingleton('checkout/session')
                ->addNotice($this->__('Product(s) Added successfully'));
            $this->_redirect('wishlist/index');
        }
    }
	
}