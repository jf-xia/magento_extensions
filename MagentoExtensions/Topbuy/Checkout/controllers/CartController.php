<?php 
require_once "Mage/Checkout/controllers/CartController.php";
class Topbuy_Checkout_CartController extends Mage_Checkout_CartController
{
    /**
     * Add product to shopping cart action
     */
    public function addajaxAction()
    {
        $cart   = $this->_getCart();
		 
        $params = $this->getRequest()->getParams();
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();

            /**
             * Check product availability
             */
            if (!$product) {
//                $this->_goBack();
                return;
            }
			
            $cart->addProduct($product, $params);

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);
            
			echo '<script>
					//close quick view
					cleanQuickview();
					jQuery("#topbuy-quickview").hide(); 
					jQuery("#quickview_prd_img").attr("src","'.Mage::helper('catalog/image')->init($product, 'small_image')->resize(60, 60).'");
					jQuery("#quickview_prd_name").html("'.$product->getName().'");
					jQuery("#quickview_prd_qty").html("'."1".'");
					jQuery("#quickview_prd_price").html("$'.Mage::getModel('directory/currency')->format($product->getFinalPrice(), array('display'=>Zend_Currency::NO_SYMBOL), false).'");  
					jQuery("#topbuy-quickview-complete").fadeIn(1000).delay(3000).fadeOut(500);					
					</script>';
			
			/*
            echo '<br><br>The item:<br>'.$product->getName().'
                <br>Quantity: 1<br>has been successfully added to your shopping cart.
                <br>You can <b><a id="continue-shopping" href="javascript:void(0)">Continue Shopping</a></b> 
                <b id="go-checkout" > or <a href="'.Mage::getBaseUrl().'checkout/cart/">Go For Checkout</a></b>
                    <div class="shadowbox-close">CLOSE</div>';
			*/
            /**
             * @todo remove wishlist observer processAddToCart
             */
//            Mage::dispatchEvent('checkout_cart_add_product_complete',
//                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
//            );
//
//            if (!$this->_getSession()->getNoCartRedirect(true)) {
//                if (!$cart->getQuote()->getHasError()){
//                    
//                }
//                $this->_goBack();
//            }
//            $this->isnocoupon();
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $e->getMessage();
        }
    }

//	public function indexAction()
//    { 
//		//add by richard to record avail track
//		$emark_tracking_code  = (string) $this->getRequest()->getParam('emark_tracking_code',"");
//		Mage::register('emark_tracking_code', $emark_tracking_code);
//		$idtbproduct  = (string) $this->getRequest()->getParam('idtbproduct',"");
//		Mage::register('idtbproduct', $idtbproduct);
//		//end avail
//        $cart = $this->_getCart();
//        if ($cart->getQuote()->getItemsCount()) {
//            $cart->init();
//            $cart->save();
//
//            if (!$this->_getQuote()->validateMinimumAmount()) {
//                $warning = Mage::getStoreConfig('sales/minimum_order/description');
//                $cart->getCheckoutSession()->addNotice($warning);
//            }
//        }
//
//        // Compose array of messages to add
//        $messages = array();
//        foreach ($cart->getQuote()->getMessages() as $message) {
//            if ($message) {
//                $messages[] = $message;
//            }
//        }
//        $cart->getCheckoutSession()->addUniqueMessages($messages);
//
//        /**
//         * if customer enteres shopping cart we should mark quote
//         * as modified bc he can has checkout page in another window.
//         */
//        $this->_getSession()->setCartWasUpdated(true);
//
////            $this->isnocoupon();
//        Varien_Profiler::start(__METHOD__ . 'cart_display');
//        $this
//            ->loadLayout()
//            ->_initLayoutMessages('checkout/session')
//            ->_initLayoutMessages('catalog/session')
//            ->getLayout()->getBlock('head')->setTitle($this->__('Shopping Cart'));
//        $this->renderLayout();
//        Varien_Profiler::stop(__METHOD__ . 'cart_display');
//    }
//	
//    /**
//     * Set back redirect url to response
//     *
//     * @return Mage_Checkout_CartController
//     */
//    protected function _goBack()
//    {
//        $returnUrl = $this->getRequest()->getParam('return_url');
////        Mage::getSingleton('checkout/session')->setBasket((int)$this->getRequest()->getParam('product').'_'.(int)$this->getRequest()->getParam('qty'));
//        
//        if ($this->getRequest()->getParam('qty')){
//            Mage::getSingleton('checkout/session')->setBasket((int)$this->getRequest()->getParam('product').'_'.(int)$this->getRequest()->getParam('qty'));
//        }
//        
//        if ($returnUrl) {
//            // clear layout messages in case of external url redirect
//            if ($this->_isUrlInternal($returnUrl)) {
//                $this->_getSession()->getMessages(true);
//            }
//            $this->getResponse()->setRedirect($returnUrl);
//        } elseif (!Mage::getStoreConfig('checkout/cart/redirect_to_cart')
//            && !$this->getRequest()->getParam('in_cart')
//            && $backUrl = $this->_getRefererUrl()
//        ) {
//            $this->getResponse()->setRedirect($backUrl);
//        } else {
//            if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
//                $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
//            }
//            
//		    $emark_tracking_code  =  $this->getRequest()->getParam('emark_tracking_code');
//			//echo $emark_tracking_code;
//			//die();
//			$param = array();
//			if( $emark_tracking_code!="")
//			{
//				$product = $this->_initProduct();
//				$idtbproduct = $product->getIdtbproduct();
//				$param['idtbproduct']  = $idtbproduct ;
//				$param['emark_tracking_code']  = $emark_tracking_code ;
//				} 
//			$this->_redirect('checkout/cart',$param); 
//        }
//        return $this;
//    } 
    
    public function isnocoupon() {
//        Mage::getSingleton('checkout/session')->setIsNoCoupon(1);
//        $nocoupon=array();
//        $dealModel=Mage::getModel('homepage/stealday')->getCollection();
//        $dealModel->getSelect()->where('fromdate>=?', Mage::getModel('core/date')->date());
//        foreach ($dealModel as $item) {
//            $nocoupon[] = $item->getIdproduct();
//        }
//        $quote = Mage::getSingleton('checkout/session')->getQuote();
//        foreach ($quote->getAllItems() as $item) {
//            $isCoupon = in_array($item->getProductId(),$nocoupon);
//            if ($isCoupon){
//                Mage::getSingleton('checkout/session')->setIsNoCoupon(0);
//                Mage::getModel('checkout/cart_coupon_api')->remove($quote->getId());
//            }
//        }
    }
}