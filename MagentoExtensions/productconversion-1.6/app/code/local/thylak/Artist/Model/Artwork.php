<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * Artwork model
 * 
 * Artist Artwork
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_Model_Artwork extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('artist/artwork');
    }
	
	
	
	 private $formdata=array();
	function __isset($key)
	{
		
		return $this->formdata[$key]; 
	}
    function __set($key,$value)
    {
		
		$this->formdata[$key] = $value;
    }
   
   
   
/**
 * Create Simple Product
 *
 * @param   int $i, string $title
 * 
 * @return  simple product id $id
*/
	public function createSimpleProduct()
	{
		$filename = $this->formdata['title'];
		$i = $this->formdata['i'];
		$sku = $filename.$i;
		$name = $filename.$i;
		if($i==0){
			$price = 17;
			$color = 3;
		}
		if($i==1){
			$price = 30;
			$color = 4;
		}
		
	 	$product = new Mage_Catalog_Model_Product();
		$product->setSku($sku);
		$product->setAttributeSetId(4);
		$product->setColor($color);
		$product->setTypeId('simple');
		$product->setName($name);
		$product->setCategoryIds(array(4)); # some cat id's, my is 7
		$product->setWebsiteIDs(array(1)); # Website id, my is 1 (default frontend)
		$product->setDescription('Full description here');
		$product->setShortDescription('Short description here');
		$product->setPrice($price); # Set some price    
		//Default Magento attribute
		$product->setWeight(2);
		$product->setVisibility(4);
		$product->setStatus(1);
		$product->setTaxClassId(0); # My default tax class
		$product->setStockData(array(
			'is_in_stock' => 1,
			'qty' => 99
		));
		$product->setCreatedAt(strtotime('now'));
		
		try {
					
					
					$product->save();
                    $id = $product->getId();
                    return $id;
				}
				catch (Exception $ex) {  
					//Handle the error
			}
	}
    
/**
 * Create Configurable Product
 *
 * @param   string $title
 * 
 * Color Attribute id is 80 
 * 
 * @return  configurable product id $id
*/    
    
	public function createConfigurableProduct()
	{
	$filename = $this->formdata['title'];
		$pid = $this->formdata['pid'];
      //  $filename = $title;
		//ini_set('memory_limit', '128M');
		Mage::app();
	    $product = Mage::getModel('catalog/product');
	    $product->setTypeId('configurable');
	    $product->setTaxClassId(0);
	    $product->setWebsiteIds(array(1)); 
	    $product->setAttributeSetId(4); 
	    $product->setSku($filename);
	    $product->setName($filename);
	    $product->setDescription('Shirt');
	    $product->setInDepth("shirt test");    
	    $product->setPrice("150");
	    $product->setShortDescription('shirt');
	    $product->setWeight(0);
	    $product->setStatus(1); //enabled
	    $product->setVisibility(4); //catalog and search
	    $product->setMetaDescription("shirt");
	    $product->setMetaTitle("shirt");
	    $product->setMetaKeywords("shirt test");
        $data = array($pid[0]=>
               array('0'=>
                        array('attribute_id'=>'80','label'=>'Yellow','value_index'=>'3','is_percent'=>0,'pricing_value'=>'10'),
                ),
               $pid[1]=>array('0'=>
                        array('attribute_id'=>'80','label'=>'Red','value_index'=>'4','is_percent'=>0,'pricing_value'=>'15'),
               )
            );
	    $product->setConfigurableProductsData($data);
            $data = array('0'=>array('id'=>NULL,'label'=>'Color','position'=> NULL,
                   'values'=>array('0'=>
                                            array('value_index'=>3,'label'=>'Yellow','is_percent'=>0,
                                                    'pricing_value'=>'10','attribute_id'=>'80'),
                                        '1'=>
                                            array('value_index'=>4,'label'=>'Red',
			                            'is_percent'=>0,'pricing_value'=>'15','attribute_id'=>'80')
		    ),
                    'attribute_id'=>80,'attribute_code'=>'color','frontend_label'=>'Color',
		    'html_id'=>'config_super_product__attribute_0')
             );
	    $product->setConfigurableAttributesData($data);
	    $product->setCanSaveConfigurableAttributes(1);
 
	    try{
	    	$product->save();
                $productId = $product->getId();
	    	    $id = $product->getId();
				return $id;
	    }
	    catch (Exception $e){ 		
	        //print "exception:$e";
	    } 
	
	}
	
	public function uploadArtwork()
	{
	
	try {    
              $imagename = $this->formdata['imagename'];
			    $artistid = $this->formdata['artistid'];
				    /* Starting upload */    
                    $uploader = new Varien_File_Uploader('imagename');
                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . $artistid . DS;
                  $uploader->save($path, $imagename );
                    
                } catch (Exception $e) {
              
                }  
	
	}
}