<?php

class Topbuy_Homepage_IndexController extends Mage_Core_Controller_Front_Action {

    public function LivechatAction() {
        $livechatUrl='http://119.82.150.42/livechat/livehelp.php?department=2';
        $session=Mage::getSingleton( 'customer/session' );
        if($session->isLoggedIn()) {
            $customerid = Mage::getModel('homepage/customermap')->getCollection()
                    ->addFilter('id_magcustomer',$session->getId())->getFirstItem()->getIdTbcustomer();
            $livechatUrl .= '&idcustomer='.$customerid;
            Mage::app()->getFrontController()->getResponse()->setRedirect($livechatUrl)->sendResponse();
        } else {
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
        }
    }
    
    public function OrdertrackAction() {
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Order Track"));

        $this->renderLayout(); 
    }
    
    public function ReferAction() {
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Invite Your Friends"));

        $this->renderLayout(); 
    }
    
    
    public function OldhistoryAction() {
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Order history"));

        $this->renderLayout(); 
    }
    
    public function OtrackingAction() {
        $trackemail = $this->getRequest()->getParam('trackemail');
        $trackorder = $this->getRequest()->getParam('trackorder');//100000047
        $order = Mage::getModel('sales/order')->loadByIncrementId($trackorder);
        $html = '';
        if ($order->getCustomerEmail()==$trackemail){
            $html .= '
        <div class="order-no">
          <h2>Track Result Of</h2>
          <h1>Order NO. <span>'.$trackorder.'</span></h1>
        </div>
        <div class="order-info">
          <p>
            <span><strong>Order Date:</strong>'.date('d/m/Y h:i:s', strtotime($order->getCreatedAt())).'</span>
            <span><strong>Dispatched On:</strong>'.date('d/m/Y h:i:s', strtotime($order->getUpdatedAt())+3600*24).'</span>
            <span><strong>Order State:</strong> '.strtoupper($order->getStatus()).'</span>
          </p>
          <p>
            <!--<span><strong>Payment Date:</strong>16/5/2012 08:04:10</span>-->
            <span><strong>Payment Ref.:</strong>'.strtoupper($order->getPayment()->getMethod()) .'</span>
          </p>
        </div>
        <div class="clear"></div>
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th style="text-align:left">Order Item(s)</th>
            <th>Qty</th>
            <th>EST</th>
            <th>Status</th>
            <th>Tracking NO.(if avail.)</th>
          </tr>
            ';
//            $html .= $order->getSubtotal();
//            $html .= '<br>'.print_r($order->getInvoiceCollection()->getFirstItem()). '<br>';
            $items = $order->getAllVisibleItems();
            foreach ($items as $item)
            {
                $trackNo='';
                $trackState='';
                if ($item->getIsShipped()==1) { 
                    $trackNo = $item->getTrackingnumber(); 
                    $trackState = 'Shipped'; 
                } else {
                    $trackState = 'Processing'; 
                }
                $html .= '
                    <tr>
                        <td width="50%" style="text-align:left">'.$item->getName().'</td>
                        <td width="5%">'.(int)$item->getQtyOrdered().'</td>
                        <td width="17%">'.Mage::getModel('catalog/product')->load($item->getProductId())->getEta().'</td>
                        <td width="8%">'.$trackState.'</td>
                        <td width="20%">'.$trackNo.'</td>
                    </tr>
                    <tr class="progress">
                        <td colspan="5"><div class="track-status step'.Mage::helper('ajax')->getTrackStatus($item).'"></div></td>
                    </tr>
                ';
            }
            $html .= '
                </table>
                <a id="anotherorder" class="yellowbtn new-track">Track Another Order</a>
                <div class="clear"></div>
            ';
            
        } else {
            $html .= 'Cannot find it, order no. is wrong.';
        }
        echo $html;
    }
    
    //http://www.topbuy.com.au/homepage/index/menu
    public function menuAction() {
        $_helper = Mage::helper('catalog/category');
        $_categories = $_helper->getStoreCategories();
        if (count($_categories) > 0) {
            $i = 1;
            $menu = "";
            foreach ($_categories as $_category) {
                $menu .= "<li class='main" . $i . "'>";
                $menu .= "<a href=" . $_helper->getCategoryUrl($_category) . "><span>";
                $menu .= $_category->getName();//$_category->getPosition().' '.$_category->getName();
                $menu .= "</span></a>";
                $menu .= "<div class='highlight'><a href=" . $_helper->getCategoryUrl($_category) . ">";
                $menu .= $_category->getName();//$_category->getPosition().' '.$_category->getName();
                $menu .= "</a></div><div class='submenu'>";
                $_category = Mage::getModel('catalog/category')->load($_category->getId());
                $_subcategories = $_category->getChildrenCategories();
                if (count($_subcategories) > 0) {
                    $dlCount = 0;
                    $j = 1;
                    $menu .= "<dl>";
                    foreach ($_subcategories as $_subcategory) {
                        $_subcategory = Mage::getModel('catalog/category')->load($_subcategory->getId());
                        $_sub3categories = $_subcategory->getChildrenCategories();
                        $dlCount+=count($_sub3categories);
                        $dlCount+=1;
                        //Mage::log($dlCount);
                        if ($dlCount > 18) {
                            $menu .= "</dl><dl>";
                            $j++;
                            $dlCount = count($_sub3categories);
                        }
                        $menu .= "<dt>";
                        $menu .= "<a href=" . $_helper->getCategoryUrl($_subcategory) . ">";
                        $menu .= $_subcategory->getName();//$_subcategory->getPosition().' '.$_subcategory->getName();
                        $menu .= "</a></dt>";
                        if (count($_sub3categories) > 0) {
//                            $menu3 = array();
                            foreach ($_sub3categories as $_sub3category) {
                                if ($_sub3category->getProductCollection()->getSize() > 0) {
//                                    $menu3[$_sub3category->getName()] = "<dd><a href=" . $_helper->getCategoryUrl($_sub3category) . ">".$_sub3category->getName()."</a></dd>";
                                    $menu .= "<dd>";
                                    $menu .= "<a href=" . $_helper->getCategoryUrl($_sub3category) . ">";
                                    $menu .= $_sub3category->getName();//$_sub3category->getPosition().' '.$_sub3category->getName();
                                    $menu .= "</a>";
                                    $menu .= "</dd>";
                                }
                            }
//                            ksort($menu3);
//                            foreach ($menu3 as $value) {
//                                $menu .= $value;
//                            }
                        }
                    }
                    $menu .= "</dl>";
                    if ($j < 5) {
                        $menu .="<dl><dt class='special'>Special Offer</dt>";
                        $menu .="<dd><a href='" . $_helper->getCategoryUrl($_category) . "?dir=desc&order=created_at' rel='nofollow'>New Arrivals</a></dd>";
                        $menu .="<dd><a href='" . $_helper->getCategoryUrl($_category) . "?dir=desc&order=position' rel='nofollow'>Top Sellers</a></dd>";
                        $menu .="<dd><a href='" . $_helper->getCategoryUrl($_category) . "?dir=desc&order=customerviewed' rel='nofollow'>Most Viewed</a></dd>";
                        $menu .="<dd><a href='" . $_helper->getCategoryUrl($_category) . "?dir=desc&order=productreview' rel='nofollow'>Most Reviewed</a></dd></dl>";
//                        $menu .="<dd><a href=''>Samsung LED / Plasma TV Cash Back Promotion</a></dd>";
//                        $menu .="<dd><a href=''>40% Off HDTop Set Box DVD Player Combined</a></dd>";
                    }
                }
                $menu .= "<div class='clear'></div></div></li>";
                $i++;
            }
        }
//        echo $menu;
        $filename = Mage::getBaseDir().'/skin/frontend/topbuy/default/menu.txt';
        // Let's make sure the file exists and is writable first.
        if (is_writable($filename)) {
            // In our example we're opening $filename in append mode.
            // The file pointer is at the bottom of the file hence
            // that's where $somecontent will go when we fwrite() it.
            if (!$handle = fopen($filename,'w-')) {
                echo "Cannot open file ($filename)";
                exit;
            }
            // Write $somecontent to our opened file.
            if (fwrite($handle, $menu) === FALSE) {
                echo "Cannot write to file ($filename)";
                exit;
            }
            echo "Success, wrote to file ($filename)";
            fclose($handle);
        } else {
            echo "The file $filename is not writable";
        }
    }

}