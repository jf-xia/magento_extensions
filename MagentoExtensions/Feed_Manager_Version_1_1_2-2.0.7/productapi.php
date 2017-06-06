<?php 
set_time_limit(1600);
ini_set('memory_limit', '-1');
include_once 'app/Mage.php';
umask(0);
Mage::app();


        $products = Mage::getModel('catalog/product')->getCollection();
	$products->addAttributeToSelect('*');
        $products->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED));
	
	$visibility = array(
        Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
        Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
        );
       $products->addAttributeToFilter('visibility', $visibility);
       
       $collection = Mage::getModel('catalog/product')->getCollection();
       $collection->addAttributeToSelect('manufacturer');
       $collection->addFieldToFilter(array(
        array('attribute' => 'manufacturer', 'eq' =>$designer_id),
       ));
		
	$products->load(); 

	$baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);

        $collection = Mage::getResourceModel('catalog/product_collection');

	

 $output = '<?xml version="1.0" encoding="utf-8"?>
<products>';
if (count($collection)){ 
//echo "<pre>";
 foreach ($products as $product){
 	//var_dump($product->getdata());die;
 $output .= '<product>';
     foreach ($product->getdata() as $key=>$value) {
     	if ($key!=='stock_item') {
     	//my code start
     	
     	$url = $product->getProductUrl();
     	 if (($key == 'url_path') || ($key =='url_key')){ 
     	 $value = $url;
     	 $value = str_replace('/productapi.php','',$value);
         $value = trim ($value);
     	 } 
     	
     	if ($key == 'image'){ 
     	 $value = $baseUrl."media/catalog/product".$value;
     	 //$value = str_replace('/productapi.php','',$value);
         //$value = trim ($value);
     	 }
     	 
     	 if ($key == 'thumbnail'){ 
     	 $value = $baseUrl."media/catalog/product".$value;
     	 }
     	 /*if ($key == 'manufacturer'){ 
     	 $value = $product->getAttributeText('manufacturer');
     	 }
     	 
     	 if ($key == 'brand'){ 
     	 $value = $product->getAttributeText('manufacturer');
     	 }*/
     	 if ($key == 'manufacturer'){ 
     	 $value = $product->getResource()->getAttribute('manufacturer')->getFrontend()->getValue($product);
     	 }
     	 if ($key == 'brand'){ 
     	 $value = $product->getResource()->getAttribute('brand')->getFrontend()->getValue($product);
     	 }
     	 
     	/*$search = array('&','<','>','"','\'','-','—',"'",'(',')','™','®','©');
         $replace = array('&#38;','&lt;','&gt;','&quot;','&apos;','&#45;','&#x2015;','&#39;','&#40;','&#41;','&trade','&#174;','&copy');
     	 $value = str_replace($search,$replace,$value);
     	 $value = str_replace('&','',$value);
     	 $value = str_replace('</br>','',$value);
     	 $value = str_replace('<br/>','',$value);
     	 $value = str_replace('>','',$value);
     	 $value = str_replace('<','',$value);*/
     	 $value = "<![CDATA[$value]]>";
         
     	 
     	 
     
     	 
     	 $key = str_replace('"','',$key);
     	 //my code end
         /*$search = array('ñ');
         $replace = array('hjaja');
     	 $value = str_replace($search,$replace,$value);
     	 */
     		$output .= '<'.$key.'>'.$value.'</'.$key.'>';
     		
     	}
     	
     	
     }
$categories = $product->getCategoryIds();
$output .= '<categories>';
	foreach($categories as $k => $_category_id): 
           $_category = Mage::getModel('catalog/category')->load($_category_id);
             $cat_name = $_category->getName();
             $cat_url =  $_category->getUrl();
             
             $cat_name = "<![CDATA[$cat_name]]>";
             $cat_url = "<![CDATA[$cat_url]]>";
           
            $output .= '<category>';
            $output .= '<name>'.$cat_name.'</name>';
            $output .= '<url>'.$cat_url.'</url>';
            $output .= '</category>';        
    endforeach; 
 $output .= '</categories>';
 $output .= '</product>';   
 $url = $product->getProductUrl();
 //$url = str_replace('/productapi.php','',$url);
// $url = trim ($url); 
 

 }//endforeach;
  $output .= '
  </products>';
}//endif;
//header ("Content-Type: text/xml; charset=ISO-8859-1");

header ("Content-Type: text/xml; charset=UTF-8");
print $output;




?> 