<?php
    class Click2Customer_Analytics_Model_C2cproducts_Api extends Mage_Api_Model_Resource_Abstract {
        private $ignore_attrs = array(
            'name','type_id','attribute_set_id','entity_id','entity_type_id','sku','weight','status','old_id','tax_class_id','url_key','visibility','news_from_date','news_to_date','url_path','required_options','has_options','image_label',
            'small_image_label','thumbnail_label','created_at','updated_at','price','cost','special_price','minimal_price','special_from_date','special_to_date','enable_googlecheckout',
            'meta_title','meta_keyword','meta_description','short_description','description','in_depth','thumbnail','small_image','image','gallery','custom_design','custom_design_from','custom_design_to','custom_layout_update',
            'options_container','page_layout','is_recurring','recurring_profile','gift_message_available'
        );
        
        public function items($options=array()) {
            syslog( LOG_CRIT, '==================================================================' );
            $page_size = 1000;
            $page = 1;
            if ( isset( $options['page_size'] ) ) {
                $page_size = $options['page_size'];
            }
            if ( isset( $options['page'] ) ) {
                $page = $option['page'];
            }
            $storeId    = Mage::app()->getStore()->getId();
            $product    = Mage::getModel('catalog/product');
          
            $checkedProducts = new Varien_Data_Collection();
            
            $this->_productCollection = $product->setStoreId($storeId)
                ->getCollection()
                ->setPageSize($page_size);
            $this->_productCollection->addAttributeToSelect('*')->addAttributeToFilter(
                array(
                    array( 'attribute'=>'visibility','in'=>array('2','3','4') )
                )
            )->load();  
            $count = $product->setStoreId($storeId)->getCollection()->count();
            $num_pages = ceil($count / $page_size );
            $prods = $this->_productCollection->setCurPage($page);
            $products = array();
            if ( !empty( $prods ) ) {
                foreach( $prods as $prod ) {
                    $prodId = $prod->getIdBySku($prod->getSku());
                    $prod = $prod->load($prodId);
                    $attributes = array();

                    $attrs = $prod->getAttributes(null,true);
                    foreach( $attrs as $name=>$val ) {
                        if ( !in_array( $name, $this->ignore_attrs ) ) {
                            $value = $val->getFrontEnd()->getValue($prod);
                            if ( !is_array( $value ) ) {
                                $attributes[$name] = $value;
                            }
                        }
                    }
                    $typeId = $prod->getTypeId();
                    $prod_price = $prod->getPrice();                
                    if ( $prod->getSpecialPrice() ) {
                        if ( $prod->getPriceType() == '0' ) {
                            $_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$prod->getSku());
                            $aProductIds = $_product->getTypeInstance()->getChildrenIds($_product->getId());
                            $prices = array();
                            $minPrice = 0.00;
                            $maxPrice = 0.00;
                            foreach ($aProductIds as $ids) {
                                $prices = array();
                                foreach ( $ids as $id=>$t ) {
                                    $aProduct = Mage::getModel('catalog/product')->load($id);
                                    $aPrice = $aProduct->getPriceModel()->getPrice($aProduct);
                                    array_push( $prices, $aPrice );
                                }  
                                $prodMinPrice = min( $prices );
                                $prodMaxPrice = max( $prices );
                                
                                if ( $prod->getSpecialPrice() )  {
                                    $prodMinPrice = round( $prodMinPrice - (( 100 - $prod->getSpecialPrice() ) / 100 * $prodMinPrice), 2);
                                    $prodMaxPrice = round( $prodMaxPrice - (( 100 - $prod->getSpecialPrice() ) / 100 * $prodMaxPrice), 2 );
                                }
                                
                                $minPrice += $prodMinPrice;
                                $maxPrice += $prodMaxPrice;
                            }
                            
                            $attributes['max_price'] = $maxPrice;
                            $attributes['min_price'] = $minPrice;
                            /*
                            if ( $prod->getSpecialPrice() ) {
                                $attributes['min_price'] = $minPrice - (( 100 - $prod->getSpecialPrice() ) / 100 * $minPrice);
                                $attributes['max_price'] = $maxPrice - (( 100 - $prod->getSpecialPrice() ) / 100 * $maxPrice);
                            }
                            */
                            $prod_price = $attributes['min_price'];
                        } else {
                            $prod_price = $prod->getSpecialPrice();
                        }
                    }
                    $attributes['product_type'] = $typeId;
                    $stock_qty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($prod)->getQty();
                    $product = array(
                        'sku'=>$prod->getSku(),
                        'name'=>$prod->getName(),
                        'url'=>$prod->getProductUrl(),
                        'categories'=>$prod->getCategoryIds(),
                        'price'=>$prod_price,
                        'image_url'=>$prod->getImageUrl(),
                        'description'=>$prod->getDescription(),
                        'qty'=>$stock_qty,
                        'weight'=>$prod->getWeight(),
                        'active'=>(int)$prod->status,
                        'in_stock'=>(int)$prod->is_in_stock,
                        'attributes'=>$attributes
                    );
                    array_push( $products, $product );
                }
            } 
            $retArr = array(
                'storeId'=>$storeId,
                'options'=>$options,
                'page_size'=>$page_size,
                'num_pages'=>$num_pages,
                'num_products'=>$count,
                'products'=>$products
            );  

            return $retArr;
        }
        
        public function categories($options=array()) {
            
            $retArr = array(
                'categories'=>array()
            );
            $storeId    = Mage::app()->getStore()->getId();
            $category    = Mage::getModel('catalog/category');
            $this->_categoryCollection = $category->setStoreId($storeId)
                ->getCollection();
            $cats = $this->_categoryCollection->load();
            if ( !empty( $cats ) ) {
                foreach( $cats as $cat ) {
                    $cat->load();
                        
                        $category = array(
                            'category'=>(!is_null($cat->getName()) ? $cat->getName() : '' ),
                            'code'=>$cat->getId(),
                            'parent_code'=>($cat->getParentId() == 0 ? '' : $cat->getParentId() ),
                            'url'=>$cat->getUrl()
                        );
                        
                        array_push( $retArr['categories'], $category );
                    
                }
            }
            return $retArr;
        }
        
        
    }
