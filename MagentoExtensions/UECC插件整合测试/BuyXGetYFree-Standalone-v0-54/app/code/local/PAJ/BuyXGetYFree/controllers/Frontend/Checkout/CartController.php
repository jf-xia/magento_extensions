<?php
/**
 *  GaiterJones/PAJ - http://blog.gaiterjones.com
 *  Add free/discounted product/s to cart based on BUY X quantity and get Y product/s free/discounted.
 *  Add free/discounted product/s to cart based on SPEND X amount get Y product/s free/discounted.
 *  Add free/discounted product/s to cart based on CATEGORY X get Y product/s free/discounted.
 *  Add free/discounted product/s to cart based on COUPON X get Y product/s free/discounted.
 *  Extends Mage/Checkout/CartController.php
 *  
 *  Copyright (C) 2011 paj@gaiterjones.com 25.08.2011 v0.54
 *  0.40 - Corrected problem rendering cart in versions 1.4+
 *  0.41 - Added logic to allow a maximum limit for free product buy 5 get 1 free, buy 10 get 2 free etc.
 *  0.42 - Added control to ensure product Y is unique to prevent a cart loop
 *  0.43 - Developed Coupon X Get Y Free
 *  0.44 - Developed Coupon X Get Y Free
 *  0.45 - Fixed "out of stock" bug when no product Y specified for Buy X
 *  0.46 - Tidy up.
 *  0.47 - Developed Category X Get Y Free
 *  0.48 - Developed Y qty limits for category X
 *  0.49 - cart update bugs
 *  0.50 - Fixed cart updates
 *	0.51 - Bug Fixes - cart redirect
 *	0.53 - Developed customer group check for Spend X
 *  0.54 - Translation updates.
 *
 *	This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  @category   PAJ
 *  @package    BuyXGetYFree
 *  @license    http://www.gnu.org/licenses/ GNU General Public License
 * 
 *
 */
 
require_once Mage::getModuleDir('controllers', 'Mage_Checkout').DS.'CartController.php'; 

class PAJ_BuyXGetYFree_Frontend_Checkout_CartController extends Mage_Checkout_CartController
{
    /**
     * Shopping cart display action
     */
    public function indexAction()
    {
	
		// Buy X get Y Free
		$this->buyXgetYfree();
		// Spend X get Y Free
		$this->spendXgetYfree();				
		// Coupon X get Y Free
		$this->couponXgetYfree();
		// Category X get Y Free
		$this->categoryXgetYfree();
		
			
        $cart = $this->_getCart();
        if ($cart->getQuote()->getItemsCount()) {
            $cart->init();
            $cart->save();

            if (!$this->_getQuote()->validateMinimumAmount()) {
                $warning = Mage::getStoreConfig('sales/minimum_order/description');
                $cart->getCheckoutSession()->addNotice($warning);
            }
        }		
		
		// render cart messages
        foreach ($cart->getQuote()->getMessages() as $message) {
            if ($message) {
                $cart->getCheckoutSession()->addMessage($message);
            }
        }

        /**
         * if customer enters shopping cart we should mark quote
         * as modified bc he can has checkout page in another window.
         */
        $this->_getSession()->setCartWasUpdated(true);

        Varien_Profiler::start(__METHOD__ . 'cart_display');
        $this
            ->loadLayout()
            ->_initLayoutMessages('checkout/session')
            ->_initLayoutMessages('catalog/session')
            ->getLayout()->getBlock('head')->setTitle($this->__('Shopping Cart'));
        $this->renderLayout();
        Varien_Profiler::stop(__METHOD__ . 'cart_display');
    }
	
	public function buyXgetYfree()
	{
		// BUY X quantity Get Y product/s free/discounted
		
		$cart = $this->_getCart();
		
		if (!$this->_getCart()->getQuote()->getItemsCount()) {
            // cart is empty
			return;
        }		
		
		// Get admin variables for BUY x get y free
		$buyProductXID = explode (",",Mage::getStoreConfig('buyxgetyfree_section1/general/productx_product_id'));
		$buyProductYID = explode (",",Mage::getStoreConfig('buyxgetyfree_section1/general/producty_product_id'));
		$buyProductXminQty = explode (",",Mage::getStoreConfig('buyxgetyfree_section1/general/productx_required_qty'));
		$buyProductXmaxQty = explode (",",Mage::getStoreConfig('buyxgetyfree_section1/general/productx_limit_qty'));	
		$buyProductYDescription = explode (",",Mage::getStoreConfig('buyxgetyfree_section1/general/producty_description'));
		
		$error="A BuyXGetYFree Extension cart error was detected!";		
		
		try
		{
			for($i = 0; $i < count($buyProductXID); $i++){
				if (empty($buyProductYDescription[$i])) {
					$buyProductYDescription[$i]="free gift";
				}
				if (empty($buyProductXID[$i])) {
					$buyProductXID[$i]="0";
				}
				if (empty($buyProductYID[$i])) {
					$buyProductYID[$i]="0";
				}
				if (empty($buyProductXminQty[$i])) {
					$buyProductXminQty[$i]="999";
				}
				if (empty($buyProductXmaxQty[$i])) { // if no max quantity configured set to 0
					$buyProductXmaxQty[$i]="0";
				}				
				if ($buyProductXID[$i] !="0" && $buyProductYID[$i] !="0") {	
					if ($this->isProductYUnique()) // product Y must be unique
					{
						// update the cart for this offer
						$this->buyXgetYfreeCartUpdate((int)$buyProductXID[$i],(int)$buyProductXminQty[$i],(int)$buyProductYID[$i],$buyProductYDescription[$i],(int)$buyProductXmaxQty[$i]);				
					} else {	
						$error = "Error in Buy X configuration - Product Y is not unique across all extension settings."; 	
						throw new Exception($error);
						break;
					}
				}

			}

		} catch (Exception $ex) { 
			// Catch errors
			$cart->getCheckoutSession()->addError($this->__($error));
			$this->sendErrorEmail($error);
			}
	}

	public function spendXgetYfree()
	{	
		// SPEND X quantity Get Y product/s free/discounted
		
		$cart = $this->_getCart();
 		if (!$this->_getCart()->getQuote()->getItemsCount()) {
            // cart is empty
			return;
        }
		
		
		// Get admin variables for SPEND x get y free
		$spendProductYID = explode (",",Mage::getStoreConfig('buyxgetyfree_section2/general/spend_producty_product_id'));
		$spendCartTotalRequired = explode (",",Mage::getStoreConfig('buyxgetyfree_section2/general/spend_cart_total_required'));
		$spendProductYDescription = explode (",",Mage::getStoreConfig('buyxgetyfree_section2/general/spend_producty_description'));
		$spendCustomerGroupID = explode (",",Mage::getStoreConfig('buyxgetyfree_section2/general/spend_customer_group_id'));
		
		$error="A SpendXGetYFree Extension cart error was detected!";
		
		// Spend X amount get Y Product/s free/discounted
		try
		{

			for($i = 0; $i < count($spendProductYID); $i++){
				if (empty($spendProductYDescription[$i])) {
					$spendProductYDescription[$i]="free gift";
				}
				if (empty($spendProductYID[$i])) {
					$spendProductYID[$i]="0";
				}			
				if (empty($spendCartTotalRequired[$i])) {
					$spendCartTotalRequired[$i]="50";
				}
				if ($spendProductYID[$i] !="0") {
					if ($this->isProductYUnique())
					{
						// update the cart for this offer
						$this->spendXgetYfreeCartUpdate((int)$spendProductYID[$i],(int)$spendCartTotalRequired[$i],$spendProductYDescription[$i],$spendCustomerGroupID[$i]);
					} else {	
						$error = "Error in Spend X configuration - Product Y is not unique across all extension settings."; 	
						throw new Exception($error);
						break;
					}
				}
			}

		} catch (Exception $ex) { 
			// Catch errors
			$cart->getCheckoutSession()->addError($this->__($error));
			$this->sendErrorEmail($error);
			}
	}
	
	public function couponXgetYfree()
	{
		// Use Coupon X Get Y product/s free/discounted
		
		$cart = $this->_getCart();
		
		if (!$this->_getCart()->getQuote()->getItemsCount()) {
            // cart is empty
			return;
        }
		
		// Get admin variables for COUPON x get y free
		$couponProductYID = explode (",",Mage::getStoreConfig('buyxgetyfree_section4/general/coupon_producty_product_id'));
		$couponRequired = explode (",",Mage::getStoreConfig('buyxgetyfree_section4/general/coupon_required'));
		$couponProductYDescription = explode (",",Mage::getStoreConfig('buyxgetyfree_section4/general/coupon_producty_description'));	
		
		// Coupon X get Y Free
		$error="A CouponXGetYFree Extension cart error was detected!";
		
			try
			{

				for($i = 0; $i < count($couponProductYID); $i++){
					if (empty($couponProductYDescription[$i])) {
						$couponProductYDescription[$i]="free gift";
					}
					if (empty($couponProductYID[$i])) {
						$couponProductYID[$i]="0";
					}
					if (empty($couponRequired[$i])) {
						// no coupon specified
						break;
					} else {
					}
					if ($couponProductYID[$i] !="0") {
						if ($this->isProductYUnique() )
						{
							// update the cart for this offer
							$this->couponXgetYfreeCartUpdate((int)$couponProductYID[$i],$couponRequired[$i],$couponProductYDescription[$i]);						
						} else {	
							$error = "Error in Coupon X configuration - Product Y is not unique across all extension settings."; 	
							throw new Exception($error);
							break;						
						}
					}
				}

			} catch (Exception $ex) { 
				// Catch errors
				$cart->getCheckoutSession()->addError($this->__($error));
				$this->sendErrorEmail($error);
				}
	}
	
	public function categoryXgetYfree()
	{
		// Use a category as qualifier for bonus product Y
		
		$cart = $this->_getCart();
		
		if (!$this->_getCart()->getQuote()->getItemsCount()) {
            // cart is empty
			return;
        }
		
		// Get admin variables for CATEGORY x get y free
		$categoryProductYID = explode (",",Mage::getStoreConfig('buyxgetyfree_section5/general/category_producty_product_id'));
		$productXcategoryID = explode (",",Mage::getStoreConfig('buyxgetyfree_section5/general/category_id'));
		$categoryProductYDescription = explode (",",Mage::getStoreConfig('buyxgetyfree_section5/general/category_producty_description'));	
		$maxQtyProductY = explode (",",Mage::getStoreConfig('buyxgetyfree_section5/general/category_producty_max_qty'));	
		
		// Category X get Y Free
		$error="A CategoryXGetYFree Extension cart error was detected!";
		
			try
			{

				for($i = 0; $i < count($categoryProductYID); $i++){
					if (empty($categoryProductYDescription[$i])) {
						$categoryProductYDescription[$i]="free gift";
					}
					if (empty($categoryProductYID[$i])) {
						$categoryProductYID[$i]="0";
					}
					if (empty($productXcategoryID[$i])) {
						// no category specified
						break;
					} else {
					}
					if (empty($maxQtyProductY[$i])) {
						$maxQtyProductY[$i]="1";
					}					
					if ($categoryProductYID[$i] !="0") {
						if ($this->isProductYUnique() )
						{
							// update the cart for this offer
							$this->categoryXgetYfreeCartUpdate((int)$categoryProductYID[$i],$productXcategoryID[$i],$categoryProductYDescription[$i],$maxQtyProductY[$i]);
						} else {	
							$error = "Error in Category X configuration - Product Y is not unique across all extension settings."; 	
							throw new Exception($error);
							break;						
						}
					}
				}

			} catch (Exception $ex) { 
				// Catch errors
				$cart->getCheckoutSession()->addError($this->__($error));
				$this->sendErrorEmail($error);
				}
	}	

	public function buyXgetYfreeCartUpdate($productXID, $productXminQtyRequired, $productYID, $productYDesc, $productXmaxQty)
    {
		// if max product X quantity is zero, set to infinity (and beyond)...
		if ($productXmaxQty <= 0) {
			$productXmaxQty = 999999;
		}
		$cart = $this->_getCart();
		$cart->init();

		$productYCartItemId = null;
		$productXCartId = null;
		static $lowStockWarningAmount = 5;

		//make sure there is never more than one of product Y in cart
         foreach ($cart->getQuote()->getAllItems() as $item) {
             if ($item->getProduct()->getId() == $productYID) {
               if ($item->getQty() > 1) {
                     $item->setQty(1);
                     $cart->save();
				}
             // product y exists in cart
			 $productYCartItemId = $item->getItemId();
             }
         }
		 
		// check cart contents for product X
		foreach ($cart->getQuote()->getAllItems() as $item) {

			// check if product X exists
			if ($item->getProduct()->getId() == $productXID) {
				
				$productXCartId = $item->getItemId();
				
				// is product X configurable?
				if($item->getProduct()->getTypeId() == 'configurable') {			
				
						// for configurable products, load cart as array and check for all occurences of product X
						// and quantity to determine total quantity products in cart. i.e. when product X has multiple colours
						
						$cfg_qty = 0;
						$cfg_quantities = array();

						foreach ($cart->getQuote()->getAllItems() as $cfg_item) 
						{
							$id = $cfg_item->getProduct()->getId();
							$qty = $cfg_item->getQty();
							$cfg_quantities[$id][] = $qty;
						}
						if(array_key_exists($productXID, $cfg_quantities))
						{
						// calculate product X totals from array
						$cfg_qty = array_sum($cfg_quantities[$productXID]);
						}
						
						// check if quantity of configurable product X qualifies for free product Y
					if ($cfg_qty >= $productXminQtyRequired && $cfg_qty <= $productXmaxQty) { // check product x meets min and max set quantity
						// quantity qualifies add free product Y to cart
							if ($productYCartItemId == null) {
								$product = Mage::getModel('catalog/product')
								->setStoreId(Mage::app()->getStore()->getId())
								->load($productYID);
								$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productYID);
								$qty = $stockItem->getQty();									
								// check stock quantity of product Y.
								// to do, check if product inventory is managed otherwise this can become a minus qty
									if($product->isSaleable()) {
										if ($qty >= 0 && $qty <= $lowStockWarningAmount) {
											$this->sendErrorEmail('BuyXGetYFree product is at very low stock levels. There are only ' . ($qty - 1) . ' left.');
										}
										$cart->addProduct($product);
										$cart->save();
										$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been added to your cart.'));
										session_write_close();										
										$this->_redirect('checkout/cart');

									} else {
										if ($qty == 0) {
											$this->sendErrorEmail($product->getName(). ' stock quantity is 0 and could not be added to the cart!');
											$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
											session_write_close();										
										} else {
											$this->sendErrorEmail($product->getName(). ' was not saleable and could not be added to the cart!');
											$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
											session_write_close();	
										}
									}
								}

							break;
								
						} else {
							// quantity does not qualify
							// check if free product exists
							if ($productYCartItemId != null) {
								$cart->removeItem($productYCartItemId);
								$cart->save();
								$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been removed from your cart.'));								
								session_write_close();
								$this->_redirect('checkout/cart');
							}
							if ($cfg_qty >= ($productXminQtyRequired-1) && $cfg_qty <= $productXmaxQty) {
								// one more required for free gift prompt
								$cart->getCheckoutSession()->addNotice($this->__('Buy one more'). ' '. $item->getName(). ' '. $this->__('to qualify for a '. $productYDesc) .'!');
								session_write_close();
							}

							break;								
						}
					
				} else {	// product is not configurable
				
					if ($item->getQty() >= $productXminQtyRequired && $item->getQty() <= $productXmaxQty ) {
						// quantity qualifies so add free product Y
						if ($productYCartItemId == null) {
						    $product = Mage::getModel('catalog/product')
								->setStoreId(Mage::app()->getStore()->getId())
								->load($productYID);
							$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productYID);
							$qty = $stockItem->getQty();
							// check stock quantity of product Y.
								if($product->isSaleable()) {
									if ($qty >= 0 && $qty <= $lowStockWarningAmount) {
										$this->sendErrorEmail('BuyXGetYFree product is at very low stock levels. There are only ' . ($qty - 1) . ' left.');
									}
										$message=$this->__('Your '. $productYDesc. ' has been added to your cart.');
										$cart->addProduct($product);
										$cart->save();
										$cart->getCheckoutSession()->addSuccess($message);
										session_write_close();
										$this->_redirect('checkout/cart');
								} else {
										if ($qty == 0) {
											$this->sendErrorEmail($product->getName(). ' stock quantity is 0 and could not be added to the cart!');
											$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
											session_write_close();										
										} else {
											$this->sendErrorEmail($product->getName(). ' was not saleable and could not be added to the cart!');
											$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
											session_write_close();	
										}
								}
						}
					} else {
							// quantity does not qualify
							// check if free product exists
							if ($productYCartItemId != null) {
								$cart->removeItem($productYCartItemId);
								$cart->save();
								$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been removed from your cart.'));								
								session_write_close();								
								$this->_redirect('checkout/cart');
							}
							if ($item->getQty() >= ($productXminQtyRequired-1) && $item->getQty() <= $productXmaxQty) {
								// one more required for free gift prompt
								$cart->getCheckoutSession()->addNotice($this->__('Buy one more'). ' '. $item->getName(). ' '. $this->__('to qualify for a '. $productYDesc) .'!');
								session_write_close();
							}
					}
				}
			}
		// continue checking cart.
		}
		
		// finished checking cart.
		// if product X not in cart check for product Y and remove
		if (Mage::getStoreConfig('buyxgetyfree_section3/general/allow_duplicate_product_y'))
		{ // allow product y to be duplicated in cart without product x
		  // TO DO for development
		  // can be removed
		} else {
			if ($productXCartId == null) {
				foreach ($cart->getQuote()->getAllItems() as $item) {
				 if ($item->getProduct()->getId() == $productYID) {
						// remove product Y because product X no longer in cart
						$cart->removeItem($productYCartItemId);
						$cart->save();
						$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been removed from your cart.'));					
						session_write_close();
						$this->_redirect('checkout/cart');
					}
				}
			}
		}
	


	// end function	
	}	

	public function spendXgetYfreeCartUpdate($productYID,$cartTotalRequired,$productYDesc,$customerGroupID)
    {
		$cart = $this->_getCart();
		$cart->init();

        $productYCartItemId = null;
		static $lowStockWarningAmount = 5;

		//make sure there is never more than one of product Y in cart
        foreach ($cart->getQuote()->getAllItems() as $item) {
            if ($item->getProduct()->getId() == $productYID) {
				if ($item->getQty() > 1) {
                    $item->setQty(1);
                    $cart->save();
				}
				// product y exists in cart
				$productYCartItemId = $item->getItemId();
            }
		}
 
        // get subtotal
        $subtotal = $cart->getQuote()->getSubtotal();

		// check subtotal and customer group check qualify for offer
        if ($subtotal >= $cartTotalRequired && $this->checkCustomerGroupId($customerGroupID)) {

			if ($productYCartItemId == null) {
				$product = Mage::getModel('catalog/product')
					->setStoreId(Mage::app()->getStore()->getId())
					->load($productYID);
                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productYID);
                $qty = $stockItem->getQty();
					// check stock quantity of product Y.
						if($product->isSaleable()) {
							if ($qty <= $lowStockWarningAmount) {
								$this->sendErrorEmail('BuyXGetYFree product is at very low stock levels. There are only ' . ($qty - 1) . ' left.');
							}
							$cart->addProduct($product);
							$cart->save();
							$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been added to your cart.'));							
							session_write_close();
							$this->_redirect('checkout/cart');
						} else {
								if ($qty == 0) {
									$this->sendErrorEmail($product->getName(). ' stock quantity is 0 and could not be added to the cart!');
									$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
									session_write_close();									
								} else {
									$this->sendErrorEmail($product->getName(). ' was not saleable and could not be added to the cart!');
									$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
									session_write_close();	
								}
						}
			}

		} else {   //remove product if it is already there because the subtotal is less than the threshold
				if ($productYCartItemId != null) {
					$cart->removeItem($productYCartItemId);
					$cart->save();
					$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been removed from your cart.'));										
					session_write_close();
					$this->_redirect('checkout/cart');
				}
        }
    // end function  	
	}
	
	public function categoryXgetYfreeCartUpdate($productYID,$productXcategoryID,$productYDesc,$maxQtyProductY)
    {		
		// init cart
		$cart = $this->_getCart();
		$cart->init();

        $productYCartItemId = null;
		$categoryX=false; // reset category flag
		$categoryXcount=0;
		$productYCartQty=0;
		
		static $lowStockWarningAmount = 5;

		// loop throught the cart to get total of qualifying product X
        foreach ($cart->getQuote()->getAllItems() as $item) {
			
			// determine category ids for product
			$categoryIds = $item->getProduct()->getCategoryIds();			
			foreach($categoryIds as $categoryId)
			{
				
				if ($categoryId==$productXcategoryID)
				{
					$categoryX=true; // matching category X set flag to true
					// get true count of product x
					$categoryXcount = $categoryXcount + $item->getQty();
				}
			}
		}

		// loop through the cart to control product Y total
        foreach ($cart->getQuote()->getAllItems() as $item) {			
            if ($item->getProduct()->getId() == $productYID) {
				if ($item->getQty() > $maxQtyProductY) {
					if ($categoryXcount > $maxQtyProductY)
					{
                    	$item->setQty($maxQtyProductY);
					} else {
                    	$item->setQty($categoryXcount);
					}
					if ($maxQytProductY > 1)
					{
						$cart->getCheckoutSession()->addSuccess($this->__('You have reached your '. $productYDesc. ' limit.'));
						session_write_close();							
					}
	                $cart->save();					
					return; // product y at max qty so nothing else to do, return
				}
				// product y exists in cart
				$productYCartItemId = $item->getItemId();
				$productYCartQty = $item->getQty();
				// continue checking....
            }		
		}
		
		// debug notice, remove.
		//$cart->getCheckoutSession()->addNotice($this->__('y qty='. $productYCartQty. ' | x qty='. $categoryXcount. ' | max qty Y '.  $maxQtyProductY ));
		//session_write_close();
		
        if ($categoryX) { // products in category X exist in cart
			if ($productYCartItemId == null) { // product Y is not in the cart so we need to add it
				$product = Mage::getModel('catalog/product')
					->setStoreId(Mage::app()->getStore()->getId())
					->load($productYID);
                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productYID);
                $qty = $stockItem->getQty();
					// check stock quantity of product Y.
						if($product->isSaleable()) {
							if ($qty <= $lowStockWarningAmount) {
								$this->sendErrorEmail('BuyXGetYFree product is at very low stock levels. There are only ' . ($qty - 1) . ' left.');
							}
							$cart->addProduct($product);
							$cart->save();
							$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been added to your cart.'));							
							session_write_close();
							$this->_redirect('checkout/cart');
						} else {
								if ($qty == 0) {
									$this->sendErrorEmail($product->getName(). ' stock quantity is 0 and could not be added to the cart!');
									$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
									session_write_close();									
								} else {
									$this->sendErrorEmail($product->getName(). ' was not saleable and could not be added to the cart!');
									$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
									session_write_close();	
								}
						}
			} else {
				// product y is in the cart, ensure totals are correct
				if ($productYCartQty <> $categoryXcount) {
			        foreach ($cart->getQuote()->getAllItems() as $item) {
			            if ($item->getProduct()->getId() == $productYID) {
							$item->setQty($categoryXcount);
						    $cart->save();
							// improve logic here
							//$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' total has been updated.'));							
							//session_write_close();
							$this->_redirect('checkout/cart');
							return;
			            }		
					}
				}
			}

		} else {
            //there are no products belonging to category x in cart so remove product y if it is present
				if ($productYCartItemId != null) {
					$cart->removeItem($productYCartItemId);
					$cart->save();
					$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been removed from your cart.'));										
					session_write_close();
					$this->_redirect('checkout/cart');
				}
        }
    // end function  	
	}
	
	public function couponXgetYfreeCartUpdate($productYID,$couponRequired,$productYDesc)
    {
        //get coupon code currently applied to cart
		$cartCouponCode = $this->_getQuote()->getCouponCode();
		
		// init cart
		$cart = $this->_getCart();
		$cart->init();

        $productYCartItemId = null;
		static $lowStockWarningAmount = 5;

		//make sure there is never more than one of product Y in cart
        foreach ($cart->getQuote()->getAllItems() as $item) {
            if ($item->getProduct()->getId() == $productYID) {
				if ($item->getQty() > 1) {
                    $item->setQty(1);
                    $cart->save();
				}
				// product y exists in cart
				$productYCartItemId = $item->getItemId();
            }
		}
 
        if ($cartCouponCode === $couponRequired) {

			if ($productYCartItemId == null) {
				$product = Mage::getModel('catalog/product')
					->setStoreId(Mage::app()->getStore()->getId())
					->load($productYID);
                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productYID);
                $qty = $stockItem->getQty();
					// check stock quantity of product Y.
						if($product->isSaleable()) {
							if ($qty <= $lowStockWarningAmount) {
								$this->sendErrorEmail('BuyXGetYFree product is at very low stock levels. There are only ' . ($qty - 1) . ' left.');
							}
							$cart->addProduct($product);
							$cart->save();
							$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been added to your cart.'));							
							session_write_close();
							$this->_redirect('checkout/cart');
						} else {
								if ($qty == 0) {
									$this->sendErrorEmail($product->getName(). ' stock quantity is 0 and could not be added to the cart!');
									$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
									session_write_close();										
								} else {
									$this->sendErrorEmail($product->getName(). ' was not saleable and could not be added to the cart!');
									$cart->getCheckoutSession()->addNotice($this->__($productYDesc. ' is out of stock and cannot be added to the cart!'));
									session_write_close();	
								}
						}
			}

		} else {
            //remove product if it is already there because the coupon code is not valid
				if ($productYCartItemId != null) {
					$cart->removeItem($productYCartItemId);
					$cart->save();
					$cart->getCheckoutSession()->addSuccess($this->__('Your '. $productYDesc. ' has been removed from your cart.'));										
					session_write_close();
					$this->_redirect('checkout/cart');
				}
        }
    // end function  	
	}	

    public function sendErrorEmail($message)
    {
		if (Mage::getStoreConfig('buyxgetyfree_section3/general/send_alert_email')) {
			$message = wordwrap($message, 70);
			$from = "buyxgetyfree@gaiterjones.com";
			$headers = "From: $from";

			mail(Mage::getStoreConfig('trans_email/ident_general/email'), 'Alert from BuyXGetYFree Extension', $message, $headers);
		}
	}
	
	public function isProductYUnique()
	{
		
		if (Mage::getStoreConfig('buyxgetyfree_section3/general/allow_duplicate_product_y'))
		{
			// do nothing, returning true here to allow duplicates will create a nasty cart loop.
		}
		
		// check product Y is unique across all arrays
		$buyProductYID = explode (",",Mage::getStoreConfig('buyxgetyfree_section1/general/producty_product_id'));
		$spendProductYID = explode (",",Mage::getStoreConfig('buyxgetyfree_section2/general/spend_producty_product_id'));	
		$couponProductYID = explode (",",Mage::getStoreConfig('buyxgetyfree_section4/general/coupon_producty_product_id'));
		$categoryProductYID = explode (",",Mage::getStoreConfig('buyxgetyfree_section5/general/category_producty_product_id'));		
		
		$result = array_merge((array)$buyProductYID, (array)$spendProductYID, (array)$couponProductYID, (array)$categoryProductYID);
		
		foreach ($result as $key=>$val )
		{ 
			if (empty($val)) unset($result[$key] ); 
		}
		if ($this->isUnique($result) == true )
		{	// product Y must be unique across all offers
			return false;
		} else {
			return true;
		}
	}
	
	public function isUnique($array)
	{
     return (array_unique($array) != $array);
	}
	 

	private function checkCustomerGroupId($requiredGroupId)
	{
		// required group ID not configured
		if(empty($requiredGroupId)) { return true; }

		$requiredGroupId = explode ("+",$requiredGroupId);
		$groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
		for($i = 0; $i < count($requiredGroupId); $i++){
			if($groupId == (int)$requiredGroupId[$i])
			{
				// group match found
				return true;
			}
		}
		// no group match found
		return false;
	}
	
// end class	
}
