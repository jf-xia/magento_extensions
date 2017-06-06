<?php
/**
 * Frontbanner Sync Program with TopBuy , used to sync all necessnary information from TopBuy Backend to Magento
 * Sync based on Magento V2 Soap API based on WS-I
 * @category   TopBuy
 * @package    API Sync with TopBuy
 * @createdate 2012-03-09
 * @lastUpdate 2012-04-05
 * @author Richard Liu
 */
class Topbuy_Homepage_Model_Tbdata_Api extends Topbuy_Homepage_Model_Api_Resource
{
     /**
     * method Name
     * Create Frontbanner
     * @param : all params from table tb_frontbanner, which need sync from topbuy to magento
     * @return : true / false, indicate topbuy backend to resync if false
     */	  
    public function createBanner($rowid,$imageurl,$linkurl,$alttext,$displayfrom,$displayto, $positiontype,$displaytitle,$displaycontent)
    { 
		//echo "starts now";
		//$tbfrontbanner = Mage::getModel('homepage/frontbanner')->load($rowid);
		//echo "get collection"; 
		//$tbfrontbanner->getSelect()->where('rowid=?',$rowidtest);   
		///echo "delete done";
		//echo $tbfrontbanner->getId();	 
		//test exceptoin
		//return $this->_fault('tb_not_exists');
		
		//remove old record
		$frontbanners=Mage::getModel('homepage/frontbanner')->getCollection(); 
	    $frontbanners->getSelect()->where('displayfrom<?', date("Y-m-d",strtotime($displayfrom)+1*24*3600))->where('displayfrom>?', date("Y-m-d",strtotime($displayfrom)-1*24*3600))->where('positiontype =?', $positiontype); 
 		// $frontbanners->getSelect()->where('positiontype =?', 1); 
 		
		foreach ($frontbanners->getItems() as $_frontbanner)
		{
			$_frontbanner->delete();
		}
		 
		try 
		{
			//throw new Exception("Please stop hitting me");
			//Mage::throwException($this->__('Please enter a valid email address'));
			//try assigen all values to new obj
			$newtbfrontbanner = Mage::getModel('homepage/frontbanner');
			$newtbfrontbanner->setRowid($rowid); 
			$newtbfrontbanner->setImageurl($imageurl);
			$newtbfrontbanner->setLinkurl($linkurl);
			$newtbfrontbanner->setAlttext($alttext);
			$newtbfrontbanner->setDisplayfrom($displayfrom);
			$newtbfrontbanner->setDisplayto($displayto);
			$newtbfrontbanner->setPositiontype($positiontype);
			$newtbfrontbanner->setDisplaytitle($displaytitle);
			$newtbfrontbanner->setDisplaycontent($displaycontent);   
			//save new banner
			$newtbfrontbanner->save();                        
		}
		catch (Mage_Core_Exception $e)
		{ 
			$this->_fault('tb_data_invalid', $e->getMessage());
			return false;
		}

        return true;
    }    
 
	/**
     * method Name
     * delete Frontbanner
     * @param : Pri_key
     * @return : true/false: indicate re-sync when return false
     */	
	public function deleteBanner( $rowid )
    { 
 		$tbfrontbanner = Mage::getModel('homepage/frontbanner')->load($rowid);
        
        if (!$tbfrontbanner->getRowid()) {
			//_fault('errorName','errorDesc')
			// errorDesc can be empty, then default desc in api.xml will show.
            $this->_fault('tb_not_exists');
            // No frontbanner found
			return false;
        } 
        try {
            $tbfrontbanner->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('tb_not_deleted', $e->getMessage());
            // Some errors while deleting.
			return false;
        }
        return true;
    }
	
	 /**
     * method Name
     * Create Categoryspecial
     * @param : all params from table tb_Categoryspecial, which need sync from topbuy to magento
     * @return : true / false, indicate topbuy backend to resync if false
     */	  
    public function createCategoryspecial($rowid,$idparentcategory,$linkname,$linkhref,$linkflag,$linkstatus,$sortby, $linenumber)
    {   
		$objVals = Mage::getModel('homepage/categoryspecial')->getCollection(); 
		if($linkflag == 0)
		{
			$objVals->getSelect()->where('linenumber=?',$linenumber)->where('sortby=?',$sortby)->where('linkflag=?',0);
			foreach ($objVals->getItems() as $_objVal)
			{
				$_objVal->delete();
			}
		}
		elseif($linkflag == 1)
		{
			$objVals->getSelect()->where('linenumber=?',$linenumber)->where('linkflag=?',1);
			foreach ($objVals->getItems() as $_objVal)
			{
				$_objVal->delete();
			}
			$objVals2 = Mage::getModel('homepage/categoryspecial')->getCollection(); 
			$objVals2->getSelect()->where('linenumber=?',$linenumber)->where('linkflag=?',2);
			foreach ($objVals2->getItems() as $_objVal)
			{
				$_objVal->delete();
			}
		}
		
		 
		try 
		{
			$objVal = Mage::getModel('homepage/categoryspecial');
			$objVal->setRowid($rowid); 
			$objVal->setIdparentcategory($idparentcategory);
			$objVal->setLinkname($linkname);
			$objVal->setLinkhref($linkhref);
			$objVal->setLinkflag($linkflag);
			$objVal->setLinkstatus($linkstatus);
			$objVal->setSortby($sortby);
			$objVal->setLinenumber($linenumber); 
			//save new banner
			$objVal->save();   
		}
		catch (Mage_Core_Exception $e)
		{ 
			$this->_fault('tb_data_invalid', $e->getMessage());
			return false;
		}

        return true;
	}
	
	/**
     * method Name
     * Delete Category Special
     * @param : PK
     * @return : true / false, indicate topbuy backend to resync if false
     */
	public function deleteCategoryspecial( $rowid )
    {
		$objVal = Mage::getModel('homepage/categoryspecial')->load($rowid);
        
        if (!$objVal->getRowid()) {
			//_fault('errorName','errorDesc')
			// errorDesc can be empty, then default desc in api.xml will show.
            $this->_fault('tb_not_exists');
            // No frontbanner found
			return false;
        } 
        try {
            $objVal->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('tb_not_deleted', $e->getMessage());
            // Some errors while deleting.
			return false;
        }
        return true;
	}
	
	/**
     * method Name
     * Create Testimonial
     * @param : all params from table Testimonial, which need sync from topbuy to magento
     * @return : true / false, indicate topbuy backend to resync if false
     */
	public function createTestimonial($idtestimonial,$subject,$contentbody,$senddate,$staffid,$fromstate, $fromname,$idstore)
    {
		$objVal = Mage::getModel('homepage/testimonial')->load($idtestimonial);
		if ($objVal->getIdtestimonial() > 0 ) {
			$objVal->delete();          
        }  
		try 
		{
			$objVal = Mage::getModel('homepage/testimonial');
			$objVal->setIdtestimonial($idtestimonial); 
			$objVal->setSubject($subject);
			$objVal->setContentbody($contentbody);
			$objVal->setSenddate($senddate);
			$objVal->setStaffid($staffid);
			$objVal->setFromstate($fromstate); 
			$objVal->setFromname($fromname); 
			$objVal->setIdstore($idstore); 
			//save new banner
			$objVal->save();   
		}
		catch (Mage_Core_Exception $e)
		{ 
			$this->_fault('tb_data_invalid', $e->getMessage());
			return false;
		}

        return true;
	
	}
	
	/**
     * method Name
     * Delete Testmonila
     * @param : PK
     * @return : true / false, indicate topbuy backend to resync if false
     */
	public function deleteTestimonial( $rowid )
    {		
	
		$objVal = Mage::getModel('homepage/testimonial')->load($rowid);        
        if (!$objVal->getRowid()) {
			//_fault('errorName','errorDesc')
			// errorDesc can be empty, then default desc in api.xml will show.
            $this->_fault('tb_not_exists');
            // No frontbanner found
			return false;
        } 
        try {
            $objVal->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('tb_not_deleted', $e->getMessage());
            // Some errors while deleting.
			return false;
        }
        return true;
		
	} 
	
	/**
     * method Name
     * Update Products
     * @param : list of products need to be updated
     * @return :string of updated products ID
     */
	public function tbUpdateProducts($products,$store = null)
	{
	    $tempString = "0";
		 
		foreach ($products as $_product)
		{ 
			//$tempString = $tempString."".$_product->price; 
			//$tempString = $tempString."a"."<br/>"; 
			if($_product->idproduct != "")				
			{
				$storeId = 0;  //changed to 0 as default.
				$update_product = Mage::getModel('catalog/product')->load($_product->idproduct);
				$update_product->setStoreId($storeId);
				$update_product->setPrice($_product->price);
				$update_product->setShippingtype($_product->shippingtype);
				if($_product->tax_class_id == "0" || $_product->tax_class_id == "1")
				{
					$update_product->setTaxClassId($_product->tax_class_id);
					}
				
				$productname = $_product->productname;
				$productdescription = $_product->productdescription;
				if(!isset($productname))
				{
					$productname = "";
					}
				if(!isset($productdescription))
				{
					$productdescription = "";
					}
				if($productname != "")
			   	{
				    $update_product->setName($productname); 
				   }
			   	if($productdescription != "")
			   	{
				    $update_product->setDescription($productdescription);
				   }
					
				if ($_product->shippingtype)
				{
					$update_product->setFreeshipping(true);
					}
				else
				{
					$update_product->setFreeshipping(false);
					} 
				$update_product->setIdtbproduct($_product->idtbproduct);
				$update_product->setImageroot($_product->imageroot);
				$update_product->setStatus($_product->active_status);
				$update_product->setFixshippingfee($_product->fixshippingfee);
				$update_product->setCapshippingfee($_product->capshippingfee); 
				$_stockData = array('qty'=>$_product->stock, 
									'is_in_stock'=>$_product->is_in_stock);
				$update_product->setStockData($_stockData);   
				
				try{
		 			$update_product->save();
					Mage::helper('homepage/api')->ReindexProductStockFromDB($_product->idproduct, $_product->stock, $_product->is_in_stock ); 
					Mage::helper('homepage/api')->ReindexProductPriceFromDB($_product->idproduct); 
					//Mage::helper('homepage/api')->ReindexCategoryProducts($_product->idproduct);
					$tempString = $tempString.",".$_product->idproduct;
				}
				catch (Mage_Core_Exception $e) {
				 	$this->_fault('status_not_changed', $e->getMessage());
				}
				//start to reindex product
				//Mage::helper('homepage/api')->ReindexProduct($_product->idproduct); 
				//Mage::helper('homepage/api')->ReindexCategoryProducts($_product->idproduct); 
			}
			/*
			$idproduct = $_product->idproduct;
			$product = Mage::getModel('catalog/product')->load($idproduct);
			$product->setData( "fixshippingfee",$_product->price);
			$product->save();
			*/
		}
		return $tempString; 
	}
	/**
     * method Name
     * get lastest newsletter Information
     * @param :  hour offset
     * @return : list of newsletter 
     */
	 public function getNewsletterList($houroffset)
	 {
		 $result = array();
		 //add by richard
		 $dateFrom = time() - houroffset*60*60;
		 $dateTo = time();
		  
		 //end
		 $newsletterReg = Mage::getModel('newsletter/subscriber')->getCollection(); 
		 $newsletterReg->getSelect()->where('change_status_at >?', date("Y-m-d H:i:s",$dateFrom));
		 foreach ($newsletterReg->getItems() as $_newsletterReg) {
           	 $row  = array();
			 $row["email"] = $_newsletterReg->getEmail();
			 $row["customerid"] = $_newsletterReg->getCustomerId();
			 $row["subscribetype"] = $_newsletterReg->getSubscribeType();
			 $row["storeid"] = $_newsletterReg->getStoreId();
			 $row["subscribestatus"] = $_newsletterReg->getSubscribeStatus();
			 $row["rowid"] = $_newsletterReg->getSubscriberId();
			 $result[] = $row;
        }
		return $result;	
	}
	
	
	/**
     * method Name
     * Sync Order information
     * @param : Order List
     * @return : true / false, indicate topbuy backend to resync if false
     */
	 
	 /*
	 definition
	 Order
	 ->idorder int
	 ->idcustomer int
	 ->paymentStatus int(0: pending, 1: authorize, 2 paid)
	 ->orderStatus	int (0: pending, 3: process , 4 shipped, 5 cancnelled)
	 ->total float
	 ->taxAmount float
	 ->shippingfee float
	 ->discountAmount float
	 ->paymentReceiveDate datetime
	 ->shippedDate datetime
	 ->billingFirstName varchar(50)
	 ->billingLastName varchar(50)
	 ->billingCompany varchar(100)
	 ->billingPhone varchar(50)
	 ->billingAddress varchar(150)
	 ->billingAddress1	 varchar(150)
	 ->billingSuburb varchar(50)
	 ->billingState varchar(4)
	 ->billingPostcode varchar(50)
	 ->billingCountryCode varchar(4)
	 ->ShippingFullName  varchar(100)
	 ->shippingCompanyName varchar(100)
	 ->shippingPhone varchar(30)
	 ->shippingAddress varchar(150)
	 ->shippingAddress1	varchar(150)
	 ->shippingSuburb varchar(50)
	 ->shippingState evarchar(4)
	 ->shippingPostcode varchar(4)
	 ->shippingCountryCode varchar(4)
	 
	 
	 order.ProductsOrdered 
	 ->idorder int
	 ->idproduct int
	 ->unitprice float
	 ->quantity  int
	 */ 
	  
	
	 public function syncOrderStatus($orders)
	 {
		 $result = "-1"; 
		 /*get orders list, compare and start to sync back to magento*/
		 unset($orders_array);
		  $orders_count = count($orders);
			if($orders_count == 1)
			{    
			$orders_array[0] = $orders;
			}else
			{
			$orders_array = $orders;
			}
		  foreach ($orders_array as $_order) 
		  { 
		  	if($_order->idorder != 0)
			{ 
			  $idorder_successful = $_order->idorder;
			  $order = Mage::getModel('sales/order')->load($_order->idorder);
			  $storeId = $order->getStoreID();
			  $items = $order->getAllItems();
			  $itemcount=count($items);
			  $name=array();
			  $unitPrice=array();
			  $sku=array();
			  $ids=array();
			  $qtyordered=array();
			  $qtyinvoiced=array();
			  $qtyrefunded=array();
			  foreach ($items as $itemId => $item)
			  {
				 
				$name[] = $item->getName();
				$unitPrice[]=$item->getPrice();
				$sku[]=$item->getSku();
				$ids[]=$item->getProductId();
				$qtyordered[]=$item->getQtyOrdered();
				$qtyinvoiced[]=$item->getQtyInvoiced();
				$qtyrefunded[]=$item->getQtyRefunded();
				$qtycancelled[]=$item->getQtyCancelled();
			  }
			   	  
			  //start to query products ordered
			  unset($products_array);
			  $products_count = count($_order->productsordered);
			  if($products_count == 1)
				{    
				$products_array[0] = $_order->productsordered;
				}
			  else
				{
				$products_array = $_order->productsordered;
				}
				
			  foreach($products_array as $_productordered)
			  {
				  if($_productordered->idproduct != "")
				  { 
					  if($_productordered->quantity > 0)
					  {
						 
						  //$result = $result."<br/>#%%: Qty > 0 ";  						  
						  //compare with $sku[] which is orginal Order
						  $index_ID = 0;
						  $new_product = 1;  //1 means new product, 1 means updated product
						  
						  while($index_ID < $itemcount )
						  {
							  
							  if($ids[$index_ID] == $_productordered->idproduct && $qtyordered[$index_ID] == $_productordered->quantity)
							  {
								  //found product, then
								  $new_product = 0; 
								  //update products order to extra added field 
								  //now update record to cancelled 
								  $orderItems = Mage::getModel('sales/order_item')->getCollection()
								   				->addAttributeToFilter('order_id',$_order->idorder)
								 				->addAttributeToFilter('product_id',  $_productordered->idproduct)
								  				->addAttributeToFilter('qty_ordered', $_productordered->quantity);
								  foreach ($orderItems as $item_each) 
								  { 
									//$item_update =	Mage::getModel('sales/order_item')->load($item_each->getItemId());
									//$item_update =	Mage::getModel('sales/order_item')->load(35);
									$item_each->setIdproductordered($_productordered->idproductordered)
											  ->setTrackingnumber($_productordered->tracking_number) 
											  ->setSerialsnumber($_productordered->serials_number)  
											  ->setCarrier("") 
											  ->setRefundflag($_productordered->is_cancelled) 
											  ->setDfdropshipflag($_productordered->dfdropshipflag) 
											  ->setInstockflag($_productordered->instockflag) 
											  ->setProcessstep($_productordered->process_step) 
											  ->setIsShipped($_productordered->is_shipped) 
											  ->setIsProcessed($_productordered->is_processed) 
											  ->setShippingtype($_productordered->shipping_type);									
									//check product is cancelled and did not sync yet  
									if($_productordered->is_cancelled || $_order->order_status == 5)
								  	{
									  if($qtycancelled[$index_ID] != $_productordered->quantity)
									  { 
											$item_each->setQtyCanceled($_productordered->quantity)
												 	  ->setQtyRefunded(0);
												  
											//$result = $result."<br/>#%%: add Refund ".$item->getSku();  
										}
									} //if($_productordered->is_cancelled || $_order->order_status == 5)
									 	     
									try {
											$item_each->save();
											//$result = $result."<br/>#%%: update product ".$item_each->getItemId(); 
										 } catch (Mage_Core_Exception $e) {
											 $idorder_successful = -1;
											//$result = $result."<br/>#%%:Error update product ".$e->getMessage();  
											$this->_fault('status_not_changed', $e->getMessage());
										}
							 		 										
									 
								  }  //foreach ($orderItems as $item) 
							   }
							  $index_ID++;
							} // while($index_ID <=$itemcount )
							 
							if ($new_product == 1)
							{
								//can not find the same product in mag, then create a new product in order
								 $_product = Mage::getModel('catalog/product')->load($_productordered->idproduct); 
								  
								  $orderItem = Mage::getModel('sales/order_item')
												->setStoreId($storeId)
												->setQuoteItemId(0)
												->setQuoteParentItemId(NULL)
												->setProductId($_productordered->idproduct)
												->setProductType($_product->getTypeId())
												->setQtyBackordered(NULL)
												->setTotalQtyOrdered($_productordered->quantity)
												->setQtyOrdered($_productordered->quantity)
												->setName($_product->getName())
												->setSku($_product->getSku())
												->setPrice($_productordered->unitprice)
												->setBasePrice($_productordered->unitprice)
												->setOriginalPrice($_productordered->unitprice)
												->setRowTotal($_productordered->unitprice * $_productordered->quantity)
												->setBaseRowTotal($_productordered->unitprice * $_productordered->quantity)
												->setIdproductordered($_productordered->idproductordered)
												->setTrackingnumber($_productordered->tracking_number) 
												->setSerialsnumber($_productordered->serials_number) 
												->setCarrier("") 
												->setRefundflag($_productordered->is_cancelled) 
												->setDfdropshipflag($_productordered->dfdropshipflag) 
												->setInstockflag($_productordered->instockflag) 
												->setProcessstep($_productordered->process_step) 
												->setIsShipped($_productordered->is_shipped) 
												->setIsProcessed($_productordered->is_processed) 
												->setShippingtype($_productordered->shipping_type) ;
										
									if ($_productordered->is_cancelled  || $_order->order_status == 5)
									{
										$orderItem->setQtyCanceled($_productordered->quantity);
										$orderItem->setQtyRefunded(0);
										$orderItem->setQtyInvoiced(0);
										}
										
									 	     
									try {
											$order->addItem($orderItem);  
											//$result = $result."<br/>#%%: update product ".$item_each->getItemId(); 
										 } catch (Mage_Core_Exception $e) {
											 $idorder_successful = -1;
											//$result = $result."<br/>#%%:Error update product ".$e->getMessage();  
											$this->_fault('status_not_changed', $e->getMessage());
										} 
									unset($orderItem);
									//$result = $result."<br/>#%%: New Product ";  
								}//if ($new_product = 1)
						}
					  else // if($_productordered->quantity > 0)
					  {//quantity < 0 then ignore in magento
						 
					  } 
					
				 }// if($_productordered->idproduct != "")
			  } //foreach($products_array as $_productordered)
			
			  
			   //start to update order information	  
			   $subTotal = $_order->order_total;
			   $gst = $_order->gst;
			   $postage = $_order->shippingfee;
			   $discount = $_order->discount_amount;
			   $totalProduct = $_order->totalproductsordered; 
			   $paymentRecieveDate = $_order->payment_receive_date;
			   $orderShippedDate = $_order->shipped_date;
			   
			  // $order_update = Mage::getModel('sales/order')->load($_order->idorder); 
			   
			   $order->setSubtotal($subTotal)
					 ->setBaseTaxAmount($gst)
					 ->setBaseShippingAmount($postage)
					 ->setShippingAmount($postage)
					 ->setShippingInclTax($postage)
					 ->setBaseShippingInclTax($postage)
					 ->setTotalQtyOrdered($totalProduct)				 
					 ->setTotalItemCount($totalProduct)
					 ->setBaseSubtotal($subTotal)
					 ->setGrandTotal($subTotal)
					 ->setBaseGrandTotal($subTotal);
	 			 
				if($paymentRecieveDate != "1/01/1900 12:00:00 AM" && $paymentRecieveDate != "")
				{
					$order->setProcessedtime(strtotime($paymentRecieveDate));
					}
				if($orderShippedDate != "1/01/1900 12:00:00 AM" && $orderShippedDate != "")
				{
					$order->setShippedtime(strtotime($orderShippedDate));
					}
				try {
					$order->save();
											//$result = $result."<br/>#%%: update product ".$item_each->getItemId(); 
					 } catch (Mage_Core_Exception $e) {
					  $idorder_successful = -1;
											//$result = $result."<br/>#%%:Error update product ".$e->getMessage();  
					 $this->_fault('status_not_changed', $e->getMessage());
					} 
				//unset($order_update);
			 
				if( $_order->order_status == 4)
				{
					//mark order as shipped			
					//$order_update = Mage::getModel('sales/order')->load($_order->idorder);
					if($order->canShip())
					{	
						$shipment = $order->prepareShipment();
						$shipment->register();		 
						$order->setIsInProcess(true);
						$order->addStatusHistoryComment('Order marked as shipped', false);		 
						
						try { 
							$transactionSave = Mage::getModel('core/resource_transaction')
												->addObject($shipment)
												->addObject($shipment->getOrder())
												->save(); 
						}catch (Mage_Core_Exception $e) {
						  $idorder_successful = -1;
												//$result = $result."<br/>#%%:Error update product ".$e->getMessage();  
						 $this->_fault('status_not_changed', $e->getMessage());
						} 
						//makr order as invoiced
						//$result = $result."<br/>#%%: Order Shipped ";  
						}
					}
			
				elseif( $_order->order_status == 5)
				{
				  //mark order as cancelled
					 // $result = $result."<br/>#%%: Apply Order Cancalled ".strval($order->canCancel());  
					    try {
							//$order_update = Mage::getModel('sales/order')->load($_order->idorder); 
							$order->setState("canceled");
							$order->setStatus("canceled");
							$order->save();
						}
						catch (Mage_Core_Exception $e)
						{
							  $idorder_successful = -1;
							  $this->_fault('status_not_changed', $e->getMessage());
							}
					 
				}
				
			$result = $result.",". $idorder_successful;
			//unset($order_update);	 
			unset($order);
			unset($transaction);
			
			}//	if($_order->idorder != 0)
		   	 
          }// foreach ($orders_array as $_order) 
		return $result; 
	 }		
	
	
	
	public function syncAttribute($attributes)
	{
	 	$set_id = 9;  //default
		$group_id = 197; //filter tab
		$result = "'xxxxStartHere'";
		
		$obj_array = Mage::helper('homepage/api')->initialArray($attributes); 
		
		foreach($obj_array as $_attribute)
		{
			
			$attributeName = $_attribute->attribute_name;
			$attributeCode= $_attribute->attribute_code;
			$options = $_attribute->options;
			$resultAttr = Mage::helper('homepage/api')->createAttribute($attributeCode,$attributeName,'dropdown','',$set_id,$group_id,$options); 
			if($resultAttr != "")
			{
				$result = $result.",'".$resultAttr."'";
			} 
		} 
		return $result;
	} 
	
	public function syncAttributeProduct($productattributes)
	{
		//return idproduct string
		$result = "0";
		$product_attributes = Mage::helper('homepage/api')->initialArray($productattributes); 
		foreach($product_attributes as $_product_attribute)
		{ 
			$idproduct = $_product_attribute->idproduct;
			if (isset($idproduct))
			{
				$result = $result.",".Mage::helper('homepage/api')->updateProductFilterBatch($idproduct, $_product_attribute->attributes); 
			}
		}  
		return $result; 
	}
	
	public function syncAttributeCategory($categoryattributes)
	{
		//return idcategory string, should like 0,1,2,3,4,5
		$result = "0";
		$category_attributes = Mage::helper('homepage/api')->initialArray($categoryattributes); 
		foreach($category_attributes as $_category_attribute)
		{ 
			$idcategory = $_category_attribute->idcategory;
			$attributes = $_category_attribute->attributes;
			if (isset($idcategory) && isset($attributes))
			{				
				$result = $result.",".Mage::helper('homepage/api')->updateCategoryFilterBatch($idcategory, $attributes); 
			}
		 }  
		return $result; 
	}
	 
	public function syncDailySteal($dailySteals)
	{
		//sync daily steal of the day, small fish, small steals
		$result = "0";
		$daily_steals = Mage::helper('homepage/api')->initialArray($dailySteals); 
		foreach($daily_steals as $_daily_steal)
		{ 
			$idproduct = $_daily_steal->idproduct; 
			$promotion_price = $_daily_steal->promotion_price; 
			$idtbproduct = $_daily_steal->idtbproduct;
			$promotion_desc = $_daily_steal->promotion_desc;
			$send_date = $_daily_steal->send_date;
			$duration = $_daily_steal->duration;
	 		$line_order= $_daily_steal->line_order; 
		 

			if (isset($idproduct) && isset($promotion_price))
			{				
				$result = $result.",".strval(Mage::helper('homepage/api')->updateDailyDeal($_daily_steal)); 
			}
		 }  
		return $result; 

		
	}
	
 
	public function syncCrossSaleGroup($crossSaleGroups)
	{
		//Sync tb_csgroup 
		$result = "0";
		$crossSaleGroups = Mage::helper('homepage/api')->initialArray($crossSaleGroups); 
		foreach($crossSaleGroups as $_crossSaleGroup)
		{ 
			$idcsgroup = $_crossSaleGroup->idcsgroup;
			$csgroupname = $_crossSaleGroup->csgroupname;
			 
			if (isset($idcsgroup) && isset($csgroupname))
			{				
				$result = $result.",".Mage::helper('homepage/api')->updateCrossSaleGroup($_crossSaleGroup); 
			}
		 }  
		return $result; 

	}
		
	public function syncCrossSaleGroupProduct($crossSaleGroupProducts)
	{
		//Sync tb_csgroupproduct 

		 
		$result = "0";
		$crossSaleGroupProducts = Mage::helper('homepage/api')->initialArray($crossSaleGroupProducts); 
		foreach($crossSaleGroupProducts as $_crossSaleGroupProduct)
		{ 
			$crosssaleproducts = $_crossSaleGroupProduct->crosssaleproducts;
			$idcsgroup = $_crossSaleGroupProduct->idcsgroup; 
			if (isset($crosssaleproducts) && isset($idcsgroup))
			{				
				$result = $result.",".Mage::helper('homepage/api')->updateCrossSaleGroupProduct($_crossSaleGroupProduct); 
			}
		 }  
		return $result; 

		
	}
		
	public function syncCrossSaleProductMap($crossSaleProductMaps)
	{
		//Sync tb_csproductmap 
		$result = "0";
		$crossSaleProductMaps = Mage::helper('homepage/api')->initialArray($crossSaleProductMaps); 
		foreach($crossSaleProductMaps as $_crossSaleProductMap)
		{ 
			$idproduct = $_crossSaleProductMap->idproduct;
			$csgroups = $_crossSaleProductMap->csgroups;
			
			if (isset($idproduct) && isset($csgroups))
			{				
				$result = $result.",".Mage::helper('homepage/api')->updateCrossSaleProductMap($_crossSaleProductMap); 
			}
		 }  
		return $result;  
	}	 

	public function syncMessageAttribute($messageStatuses, $messagePriorities, $messageTypes)
	{
		//Sync Message Type, Priority, and Status
		//sync freqence: every 6 hours
		$result = true;

		$messageStatuses = Mage::helper('homepage/api')->initialArray($messageStatuses); 
		$messagePriorities = Mage::helper('homepage/api')->initialArray($messagePriorities); 
		$messageTypes = Mage::helper('homepage/api')->initialArray($messageTypes); 
		foreach($messageStatuses as $_messageStatus)
		{ 
			if (isset($_messageStatus) )
			{				
				$result = $result&&Mage::helper('homepage/api')->syncMessageStatus($_messageStatus); 
			}
		 }  

		foreach($messagePriorities as $_messagePriority)
		{ 
			if (isset($_messagePriority) )
			{				
				$result = $result&&Mage::helper('homepage/api')->syncMessagePriority($_messagePriority); 
			}
		 }  

		foreach($messageTypes as $_messageType)
		{ 
			if (isset($_messageType) )
			{				
				$result = $result&&Mage::helper('homepage/api')->syncMessageType($_messageType); 
			}
		 } 
		 
		return $result;  
	}
		
	public function syncMessageFromTopBuy($messages)
	{
		//Sync Message Content From TopBuy, 
		$result = "0,0;";  // format like idpccomments,pccomm_idfeedback; 
		$messages = Mage::helper('homepage/api')->initialArray($messages); 
		foreach($messages as $_message)
		{ 
			if (isset($_message) )
			{				
				$result = $result.Mage::helper('homepage/api')->syncMessage($_message); 
			}
		 } 
		 return $result;
		 
	}	
	public function syncMessageFromMagento()
	{
		//retrieve Message Content From Magento, 
		//type is tbMessageArray, define in wsi.xml 
		//$result = Mage::helper('homepage/api')->getMessage(); 
		///return $result;
		 
        $result = array();
        $result = Mage::helper('homepage/api')->getMessage(); 
        return $result; 
	}	
	
	public function syncMessageRankFromMagento()
	{
		$result = array();
        $result = Mage::helper('homepage/api')->getMessageRank(); 
        return $result; 
	}
	
	public function syncNewsletter($newsletter)
	{
		foreach($newsletter as $_newsletter)
		{ 
			if (isset($_newsletter) )
			{				
				$result = $result.Mage::helper('homepage/api')->syncNewsletter($_newsletter); 
			}
		 }  
        return $result; 
	}
	
	public function syncCategoryMap($categorymap)
	{
		foreach($categorymap as $_categorymap)
		{ 
			if (isset($_categorymap) )
			{				
				$result = $result.Mage::helper('homepage/api')->syncCategoryMap($_categorymap); 
			}
		 }  
        return $result; 
	}
	
	public function syncCustomerMap($customermap)
	{
		foreach($customermap as $_customermap)
		{ 
			if (isset($_customermap) )
			{				
				$result = $result.Mage::helper('homepage/api')->syncCustomerMap($_customermap); 
			}
		 }  
        return $result; 
	}
	
	public function syncOrderMap($ordermap)
	{
		foreach($ordermap as $_ordermap)
		{ 
			if (isset($_ordermap) )
			{				
				 $result = $result.Mage::helper('homepage/api')->syncOrderMap($_ordermap); 
			}
		 }  
        return $result; 
	}
	public function createProductBatch($products)
	{
		$result = "0,0;"; 
		$products =  Mage::helper('homepage/api')->initialArray($products);
		foreach($products as $_product)
		{
			$idtbproduct= $_product->idtbproduct;
			$idmagproduct = 0;
			$type = "simple";
			$set = "9";
			$sku =  $_product->sku;
			$productData =  $_product;
			$store =  "0"; 
			if (isset($_product) && $idtbproduct != 0 && $idtbproduct != "")
			{
				 
				//return $idtbproduct.$sku.var_dump($productData);
				 
				$idmagproduct = Mage::getModel('catalog/product_api_v2')->create($type, $set, $sku, $productData, $store);
				
		 		if ($idmagproduct != 0 && $idtbproduct != 0 && $sku != "")
				{
					$result = $result.$idmagproduct.",".$idtbproduct.",".$sku.";";
				}
				 
				 
			}
		}
		return $result;
	}
	protected function _initCategory($categoryId, $store = null)
    {
        $category = Mage::getModel('catalog/category') 
            ->load($categoryId);

        if (!$category->getId()) {
            $this->_fault('not_exists');
        }

        return $category;
    }
	 

	public function updateCategoryProductBatch($categoryproducts)
	{
		$result="0";
		$categoryproducts=  Mage::helper('homepage/api')->initialArray($categoryproducts);
		 
		foreach($categoryproducts as $_categoryproduct)
		{
			$rowid = $_categoryproduct->rowid;
			$idmagproduct = $_categoryproduct->idmagproduct;
			$idmagcategory =  $_categoryproduct->idmagcategory;
			$status = $_categoryproduct->status;
			$position = "0";
			$identifierType = "0";
			if($rowid != "" && $idmagcategory != "" && $idmagproduct != "")
			{				
				if ($status == "0")
				{
					//add into categoryprouct map
					if(Mage::getModel('catalog/category_api')->assignProduct($idmagcategory, $idmagproduct))
					{
						//Mage::getModel('catalog/category_api')->updateProduct($idmagcategory, $idmagproduct);
						$result= $result.",".$rowid;
					}
				}
				elseif($status == "2")	
				{
					//remove category product map
					$category = $this->_initCategory($idmagcategory);
					
					$positions = $category->getProductsPosition();
					$productId =  $idmagproduct;
					if (!isset($positions[$productId])) {
					 
						 $result= $result.",".$rowid;
					}
					else
					{
						unset($positions[$productId]);
						$category->setPostedProducts($positions);
						try {
							$category->save();
							$result= $result.",".$rowid;
						} catch (Mage_Core_Exception $e) {
							 
						}
					} 
				}
			}
		}
		return $result;
	}
	
	
   public function updateCustomerPassword($customers_password)
	{
		//return true or false,if false, re-sync will happen
		//check duplicate record, and avoid it 
		$return_result = false;
		return $return_result;
	}

   public function updateCategoryExtraInfor($categories_extra_infor) {
        //return true or false,if false, re-sync will happen
        //check duplicate record, and avoid it 
        $categoryExtraInfors = Mage::helper('homepage/api')->initialArray($categories_extra_infor);

        foreach ($categoryExtraInfors as $categoryExtraInfor) {
            if (!Mage::helper('homepage/api')->updateCategoryExtraInfor($categoryExtraInfor))
            {				
                return 0;
            }
        }
        return 1;
    }
    
   public function updateOrderHistory($orders_history) {
        $ordersHistory = Mage::helper('homepage/api')->initialArray($orders_history);

        foreach ($ordersHistory as $orderHistory) {
            if (!Mage::helper('homepage/api')->updateOrderHistory($orderHistory))
            {				
                return 0;
            }
        }
        return 1;
    }
	public function syncEmailSendBuffer($start_rowid)
	{
		//input will by last sync rowid, 
		//query 30-50 emails and build to array, check wsi.xml for the structure "tbEmailSendBufferType"
		//return $email_send_buffer
		$email_send_buffer = "";
		return $email_send_buffer;
		
	}
	
	public function syncProductUpdates($products) {
		$result="0";
        $products = Mage::helper('homepage/api')->initialArray($products);

        foreach ($products as $_product) {
           		$return_id = Mage::helper('homepage/api')->ProductUpdates($_product);
				if($return_id != "")
				{
                	$result = $result.",".$return_id; 
				}
        }
        return  $result;
    }

	public function syncPromotionCrossSale($crossSaleProducts) {
		$result="0";
        $crossSaleProducts = Mage::helper('homepage/api')->initialArray($crossSaleProducts);

        foreach ($crossSaleProducts as $_crossSaleProduct) {
            if (!Mage::helper('homepage/api')->CrossSaleProduct($_crossSaleProduct))
            {				
                $result = $result.",".$_crossSaleProduct->rowid;
            }
        }
        return  $result;
    }
	
	public function syncProductReview($productreviews) {
		$result="0,0"; //idmag,idtb
		 
        $productreviews = Mage::helper('homepage/api')->initialArray($productreviews);

        foreach ($productreviews as $_productreview) {
			 $result = $result.";".Mage::helper('homepage/api')->ProductReview($_productreview); 
        }
        return  $result;
    }
			
	public function syncProductReviewFromMag() {
		//return array of new reviews within 1 hour
		//Type: tbProductReviewArray
		//only retrieve idtbcustomer map and idtbproduct map are sync record 
		$result =  Mage::helper('homepage/api')->ProductReviewFromMag(); 
		return $result;
	}
	
	public function syncOrderPaymentInfo($orderString) {
		$orders = explode(",", $orderString);
		$orders = Mage::helper('homepage/api')->initialArray($orders); 
		$result = array();
		foreach($orders as $_orderID)
		{
			if ($_orderID > 0)
			{ 
				$_order =  Mage::getModel('sales/order')->loadByIncrementId($_orderID);
				if ($_order->getId() > 0)
				{
					$_payment = $_order->getPayment();
					$result_unit = array();
					$result_unit['idMagOrder'] = $_order->getIncrementId();
					//$result_unit['idTBOrder'] = $_order->getIdTborder() ;
					$result_unit['method'] = $_payment->getMethod();
					$result_unit['amount'] = $_payment->getBaseAmountOrdered();
					$result_unit['transactionID'] = $_payment->getLastTransId();
					$result_unit['cc_exp_month'] = $_payment->getCcExpMonth();
					$result_unit['cc_exp_year'] = $_payment->getCcExpYear();				  
					$result_unit['cc_owner'] = $_payment->getCcOwner();
					$result_unit['cc_type'] = $_payment->getCcType();
					$result_unit['cc_beagle'] = $_payment->getCcBeagle();
					$result_unit['cc_code'] = $_payment->getCcCode();
					$result_unit['money_back'] = $_payment->getMoneyBack();
					$result_unit['money_back_response'] = $_payment->getMoneyBackResponse();
					$result_unit['money_back_refer'] = $_payment->getMoneyBackRefer();
					$result_unit['money_back_date'] = $_payment->getMoneyBackDate(); 
					array_push($result, $result_unit);
				} 
			} 
		}
		return $result;
	}
	
}

?>