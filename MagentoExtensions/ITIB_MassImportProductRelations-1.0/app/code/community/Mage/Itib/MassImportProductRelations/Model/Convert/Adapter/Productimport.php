<?php
/**
* Import Relations.php
*
* @license    http://opensource.org/licenses/osl-3.0.php open software license (OSL 3.0)
*/

class Mage_Itib_MassImportProductRelations_Model_Convert_Adapter_Productimport
extends Mage_Catalog_Model_Convert_Adapter_Product
 {

    /**
    * Save product (import)
    *
    * @param array $importData
    * @throws Mage_Core_Exception
    * @return bool
    */
    public function saveRow( array $importData )

    {


		$using_product_id1=false;
		$using_product_id2=false;
		$actionIndex=0;
		$sku1Index=1;
		$sku2Index=2;
		$relationIndex=3;
		//$success_rows=0;

         $row='';

				$data=$importData;

				foreach($data as $c=>$value)
						{
        				//echo $data[$c] . "<br />\n";

							//echo '<pre>';
							//	print_r($data);
								if(strtolower($c)=="link function")
								{


									$actionIndex=$c;
								}
								elseif(strtolower($c)=="product id 1" || strtolower($c)=="productid1")
								{

									$sku1Index=$c;
									$using_product_id1=true;

								}
								elseif(strtolower($c)=="product id 2" || strtolower($c)=="productid2")
								{
									$sku2Index=$c;
									$using_product_id2=true;
								}
								elseif(strtolower($c)=="sku 1" || strtolower($c)=="sku1")
								{
									$sku1Index=$c;


								}
								elseif(strtolower($c)=="sku 2" || strtolower($c)=="sku2")
								{
									$sku2Index=$c;

								}
				         		elseif(strtolower($c)=="link type")
								{
									$relationIndex=$c;
								}
								else
								{
									$attributes[$c]=$value;
								}


							}





						if(!isset($data[$actionIndex]))
						{


							$message="Row $row skipped, Function is not set";
							Mage :: throwException( $message );

							//continue;
						}
						if(!isset($data[$sku1Index]))
						{
							$message="Row $row skipped, SKU 1 is not set";
							Mage :: throwException( $message );
						}
						$action=$data[$actionIndex];
						$sku1=$data[$sku1Index];
						if(isset($data[$sku2Index]))
						{
							$sku2=$data[$sku2Index];
						}
						else
						{
							$sku2='';
						}
						if(isset($data[$relationIndex]))
						{
							$relation=$data[$relationIndex];
						}




						if($using_product_id1==true)
						{

							$product1=Mage::getModel('Catalog/product')->load($sku1);
							$ab=$product1->getData();
							if(isset($ab['sku']))
							{
							 $sku1=$ab['sku'];
							}
							unset($product1);
						}

						if($using_product_id2==true)
						{
							$product2=Mage::getModel('Catalog/product')->load($sku2);
							$ab=$product2->getData();
							if(isset($ab['sku']))
							{
								$sku2=$ab['sku'];
							}
							unset($product2);
						}

						$valid_actions=array('assign','update','remove','assign','list');
						$valid_relations=array('up_sell','cross_sell','related','cross');
						if(!in_array(strtolower($action),$valid_actions))
						{

							$message="Row $row skipped, $action is not valid Function ";
							Mage :: throwException( $message );

							continue;
						}

						$model = Mage::getModel('catalog/product_link_api');
						try
						{
							if($action=='list')
							{
								if($using_product_id1==true)
								{

									$list=$model->items($relation,$data[$sku1Index]);
								}
								else
								{

									$list=$model->items($relation,$sku1);
								}

								if(count($list))
									{
										foreach($list as $prod)
										{
											$prod_list[]=$prod['product_id'];
										}
										$prod_list=implode(",",$prod_list);

									}
									else
									{

									}
								if($using_product_id1)
								{
									if($prod_list=='')
									{
										$message="Row $row, $action, Product1, $data[$sku1Index], has $relation relation with 0 products";
										Mage :: throwException( $message );

									}
									else
									{
									$message="Row $row, $action, Product1, $data[$sku1Index], has $relation relation with following products ($prod_list)";
									Mage :: throwException( $message );
									}
								}
								else
								{
									if($prod_list=='')
									{
										$message.="Row $row, $action, sku1, $sku1, has $relation relation with 0 products";
										Mage :: throwException( $message );
									}
									else
									{
									$message.="Row $row, $action, sku1, $sku1, has $relation relation with following products ($prod_list)";
									Mage :: throwException( $message );
									}

								}
							}
							else
							{

								switch($action)
								{
									case 'assign':
									case 'update':
										$model->$action($relation,$sku1,$sku2,$attributes);
										break;
									case 'remove':
										if($this->_initProduct($sku2))
										{
											$model->$action($relation,$sku1,$sku2);
										}

									   break;
									default:
										$model->$action($relation,$sku1,$sku2);
								}

							}
							//$success_rows++;
						}
						catch(Exception $e)
						{


							if($e->getMessage()=='product_not_exists')
							{
								if($using_product_id1)
								{

								$message="Row $row skipped, $action, Product1, $data[$sku1Index], does not exist";
								Mage :: throwException( $message );
								}
								else
								{

									$message="Row $row skipped, $action, sku1, $sku1, does not exist ";
									Mage :: throwException( $message );
								}

							}
							if($e->getMessage()=='product2_not_exists')
							{
								if($using_product_id2)
								{

								$message="Row $row skipped, $action, Product2, $data[$sku2Index], does not exist ";
								Mage :: throwException( $message );
								}
								else
								{

									$message="Row $row skipped, $action, sku2, $sku2, does not exist";
									Mage :: throwException( $message );
								}


							}
							if($e->getMessage()=='data_invalid')
							{
								if($using_product_id2)
								{
									$message="Row $row skipped, $action, Product2, $data[$sku2Index], does not exist";
									Mage :: throwException( $message );
								}
								else
								{
									$message="Row $row skipped, $action, sku2, $sku2, does not exist";
									Mage :: throwException( $message );
								}


							}
							if($e->getMessage()=='type_not_exists')
							{
								$message="Row $row skipped, $action, $relation, is not valid relation ";
								Mage :: throwException( $message );

							}

						}
			//$message="div style='position:absolute; background:green; height:2px; width:0; top:-2px; left:-2px; overflow:hidden;'>" .
			//	"Row Imported Successfully</div>";
			//	Mage :: throwException( $message );

        return true;
      }

    protected function userCSVDataAsArray( $data )

    {
        return explode( ',', str_replace( " ", "", $data ) );
        }

    protected function skusToIds( $userData, $product )

    {
        $productIds = array();
        foreach ( $this -> userCSVDataAsArray( $userData ) as $oneSku ) {
            if ( ( $a_sku = ( int )$product -> getIdBySku( $oneSku ) ) > 0 ) {
                parse_str( "position=", $productIds[$a_sku] );
                }
            }
        return $productIds;
        }



    protected function _removeFile( $file )

    {
        if ( file_exists( $file ) ) {
            if ( unlink( $file ) ) {
                return true;
                }
            }
        return false;
    }



    protected function _initProduct($productId)
    {


        $product = Mage::getModel('catalog/product');


		if($product->getIdBySku($productId))
		{
        	$idBySku = $product->getIdBySku($productId);
		}
		else
		{
			Mage::throwException('product2_not_exists');
			return;
		}

        if ($idBySku) {
            $productId = $idBySku;
        }

        $product->load($productId);

        if (!$product->getId()) {
            Mage::throwException('product2_not_exists');
        }

        return $product;
    }


    }



