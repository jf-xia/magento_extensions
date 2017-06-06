<?php

class Topbuy_Homepage_Helper_Api extends Mage_Core_Helper_Abstract {

    public function test($inputString) {
        return $inputString;
    }

    public function initialArray($inputArray) {
        unset($obj_array);
        $obj_count = count($inputArray);
        if ($obj_count == 1) {
            $obj_array[0] = $inputArray;
        } else {
            $obj_array = $inputArray;
        }
        return $obj_array;
    }
	
	function remove_numbers($string) { 
  		$vowels = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "&","%","@","$","#");
  		$string = str_replace($vowels, 'zz', $string);
  		return $string;
  	}
    //make update filter code based on uiform rule
    public function makeAttrbuteCode($code) {
        return 'tfd_' . $this->remove_numbers($code);
    }

    public function makeAttributeFunctionName($code) {
        $functionName = 'set';
        $code = $this->makeAttrbuteCode($code);
        $pieces = explode("_", $code);
        foreach ($pieces as $_piece) {
            $functionName = $functionName . ucfirst($_piece);
        }
        return $functionName;
    }

    public function createAttribute($code, $label, $attribute_type, $product_type, $set_id, $group_id, $options) {
        $test_result = "";

        //check attribute is exists or not
        $test_attribute_model = Mage::getModel('eav/entity_attribute');
        $test_attribute_code = $test_attribute_model->getIdByCode('catalog_product', $this->makeAttrbuteCode($code));
        $test_attribute = $test_attribute_model->load($test_attribute_code);
        if ($test_attribute->getId() != "") {
            return $label;
        }

        $_attribute_data = array(
            'attribute_code' => $this->makeAttrbuteCode($code),
            'is_global' => '1',
            'frontend_input' => 'select', //'boolean', 		
            'default_value_text' => '',
            'default_value_yesno' => '0',
            'default_value_date' => '',
            'default_value_textarea' => '',
            'is_unique' => '0',
            'is_required' => '0',
            'apply_to' => array($product_type), //array('grouped')
            'is_configurable' => '0',
            'is_searchable' => '0',
            'is_filterable' => '1',
            'is_visible_in_advanced_search' => '0',
            'is_comparable' => '0',
            'is_used_for_price_rules' => '0',
            'is_wysiwyg_enabled' => '0',
            'is_html_allowed_on_front' => '1',
            'is_visible_on_front' => '0',
            'used_in_product_listing' => '0',
            'used_for_sort_by' => '0',
            //'frontend_label' => array('Old Site Attribute '.(($product_type) ? $product_type : 'joint').' '.$label)
            'frontend_label' => array($label)
        );
        $model = Mage::getModel('catalog/resource_eav_attribute');

        if (!isset($_attribute_data['is_configurable'])) {
            $_attribute_data['is_configurable'] = 0;
        }
        if (!isset($_attribute_data['is_filterable'])) {
            $_attribute_data['is_filterable'] = 0;
        }
        if (!isset($_attribute_data['is_filterable_in_search'])) {
            $_attribute_data['is_filterable_in_search'] = 0;
        }

        if (is_null($model->getIsUserDefined()) || $model->getIsUserDefined() != 0) {
            $_attribute_data['backend_type'] = $model->getBackendTypeByInput($_attribute_data['frontend_input']);
        }

        $defaultValueField = $model->getDefaultValueByInput($_attribute_data['frontend_input']);
        if ($defaultValueField) {
            //$_attribute_data['default_value'] = $this->getRequest()->getParam($defaultValueField);
        }

        if ($set_id != "") {
            $model->setAttributeSetId($set_id);
        }
        if ($group_id != "") {
            $model->setAttributeGroupId($group_id);
        }

        $model->addData($_attribute_data);

        $model->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId());
        $model->setIsUserDefined(1);

        try {
            $model->save();

            foreach ($options as $_option) {
                if ($_option->option_name != "") {
                    $value['option'] = array($_option->option_name, $_option->option_name);
                    $result = array('value' => $value);
                    $model->setData('option', $result);
                    $model->save();
                    //$test_result = $test_result.$_option->option_name;
                }
            }
            //$model->save();  
            return $label;
        } catch (Exception $e) { //echo '<p>Sorry, error occured while trying to save the attribute. Error: '.$e->getMessage().'</p>'; 
            return "<br/>Error: " . $label . $test_result . "--->" . $e->getMessage();
        }
    }

    public function attributeValueExists($arg_attribute, $arg_value) {
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
        if (isset($attribute_code)) {
            $attribute = $attribute_model->load($attribute_code);

            $attribute_table = $attribute_options_model->setAttribute($attribute);
            $options = $attribute_options_model->getAllOptions(false);

            foreach ($options as $option) {
                if ($option['label'] == $arg_value) {
                    return $option['value'];
                }
            }
        }


        return false;
    }

    public function addAttributeValue($arg_attribute, $arg_value) {
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', $arg_attribute);

        $attribute = $attribute_model->load($attribute_code);

        $attribute_table = $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);

        if (!$this->attributeValueExists($arg_attribute, $arg_value)) {
            $value['option'] = array($arg_value, $arg_value);
            $result = array('value' => $value);
            $attribute->setData('option', $result);
            $attribute->save();
        }

        foreach ($options as $option) {
            if ($option['label'] == $arg_value) {
                return $option['value'];
            }
        }
        return true;
    }

    public function getAttributeValue($arg_attribute, $arg_option_id) {
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_table = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
        $attribute = $attribute_model->load($attribute_code);

        $attribute_table->setAttribute($attribute);

        $option = $attribute_table->getOptionText($arg_option_id);

        return $option;
    }

    //update on product with attribute code and value
    public function updateProductFilter($idproduct, $attribute_code, $attribute_value) {
        $product = Mage::getModel('catalog/product')->load($idproduct);
        $attribute_name = $this->makeAttrbuteCode($attribute_code);
        $attribute_id = $this->attributeValueExists($attribute_name, $attribute_value);
        $set_function_name = $this->makeAttributeFunctionName($attribute_code);


        if ($attribute_id) { //if the attribute value does exist in magento already simply set 
            $product->$set_function_name($attribute_id);
            $product->save();
        } else {  //if not exists then save value first
            $attribute_id = $this->addAttributeValue($attribute_name, $attribute_value);
            $attribute_id = $this->attributeValueExists($attribute_name, $attribute_value);
            $product->$set_function_name($attribute_id);
            $product->save();
        }
    }

    //update on product with attribute code and value
    public function updateProductFilterBatch($idproduct, $attributes) {
        $product = Mage::getModel('catalog/product')->load($idproduct);
		$debugString = "xxx---";
        $attributeArray = $this->initialArray($attributes);
        foreach ($attributeArray as $_attribute) {
            $code = $_attribute->attribute_code;
            $value = $_attribute->attribute_value;
            $attribute_name = $this->makeAttrbuteCode($code);
			if(!$this->attributeValueExists($attribute_name, $value))
			{
				//if not option available then add new one 
				$this->addAttributeValue($attribute_name, $value);
				}
            $attribute_id = $this->attributeValueExists($attribute_name, $value);			
            $set_function_name = $this->makeAttributeFunctionName($code);
			$debugString = $debugString.$attribute_name.'--attrid--'.$attribute_id.'--funName--'.$set_function_name.'---value---'.$value;
            if ($attribute_id) { //if the attribute value does exist in magento already simply set 
                $product->$set_function_name($attribute_id);
				$debugString = $debugString."--hit---xxxx-";
            } else {  //if not exists then save value first
				$debugString = $debugString."--missed---xxxx-";
                //$attribute_id = $this->addAttributeValue($attribute_name, $attribute_value);
                //$attribute_id = $this->attributeValueExists($attribute_name, $attribute_value);  
                //$product->$set_function_name($attribute_id);
            }
        }
	
        //now it is the time to save product
        try {
            $product->save();
			//return $debugString;
            return $idproduct;
        } catch (Exception $e) {
			//return $e;
            return 0;
        }
    }

    public function updateCategoryFilterBatch($idcategory, $attributes) {
        //$idcategory=22, $attributes=array('tf_d_colour','tf_d_brand','tf_d_price','tf_d_size')
        //1.check idcateogry is valid...array_intersect($attributes,$catFilterArray)
        //  if(Mage::getModel("catalog/category")->load($idcategory)->getIsActive()==1){
        //2. loop attributes //2a. check attribute , insert new if not exists
        $attributes = $this->initialArray($attributes);
        foreach ($attributes as $key => $attribute) {
            $attributes[$key] = $this->makeAttrbuteCode($attribute->attribute_name);
        }
        $catFilterModel = Mage::getModel('homepage/categoryfilter');
        $categoryFilter = $catFilterModel->getCollection()->addFilter("cf_idcategory", $idcategory);
        $catFilterArray = array();
        foreach ($categoryFilter as $key => $_item) {
            $catFilterArray[$key] = $_item->getCfFiltername();
        }
        $addCatFilter = array_diff($attributes, $catFilterArray);
        $delCatFilter = array_diff($catFilterArray, $attributes);
//            print_r($delCatFilter);
        if (count($addCatFilter)) {
            foreach ($addCatFilter as $addattribute) {
                try {
                    Mage::getModel('homepage/categoryfilter')->setCfIdcategory($idcategory)
                            ->setCfFiltername($addattribute)->save();
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('addCatFilter', $e->getMessage());
                    return 0;
                }
            }
        }
        if (count($delCatFilter)) {
            try {
                foreach ($delCatFilter as $key => $delattribute) {
                    Mage::getModel('homepage/categoryfilter')->load($key)->delete();
                }
            } catch (Mage_Core_Exception $e) {
                $this->_fault('addCatFilter', $e->getMessage());
                return 0;
            }
        }
        //    } else {
        //        $idcategory=0;
        //   }
        return $idcategory;
    }

    public function updateDailyDeal($daily_steal) {

        $idproduct = $daily_steal->idproduct;
        $promotion_desc = $daily_steal->promotion_desc;
        $idtbproduct = $daily_steal->idtbproduct;
        $promotion_price = $daily_steal->promotion_price;
        $send_date = $daily_steal->send_date;
        $duration = $daily_steal->duration;
        $line_order = $daily_steal->line_order;
        $max_qty = $daily_steal->max_qty;
		$was_price = $daily_steal->was_price;
		$extra_description = $daily_steal->extra_description;
		/*
        $result = "<b>Inner Result</b>";
        $result = $result . "<br/>Start +++++++++++++++++++++++++++++++++++++++++++++++++++<br/>";
        $result = $result . ",idproduct: " . $idproduct . "<br/>";
        $result = $result . ",promotion_price: " . $promotion_price . "<br/>";
        $result = $result . ",promotion_desc: " . $promotion_desc . "<br/>";
        $result = $result . ",idtbproduct: " . $idproduct . "<br/>";
        $result = $result . ",send_date: " . date("Y-m-d H:i:s", strtotime($send_date) + (12 * 3600)) . "<br/>";
        $result = $result . ",duration: " . $duration . "<br/>";
        $result = $result . ",line_order: " . $line_order . "<br/>";
        $result = $result . "<br/> Complete +++++++++++++++++++++++++++++++++++++++++++++++++++<br/>";
		*/


        $fromdate = date("Y-m-d H:i:s", strtotime($send_date) + (12 * 3600));
        $todate = date("Y-m-d H:i:s", strtotime($send_date) + (24 * 3600 * ($duration + 0.5)));
        //compare input record wiht current database by send_data and idproduct
        //if exists then update
        $dailyDeal = Mage::getModel('homepage/stealday')->getCollection();
        $dailyDeal->getSelect()->where('fromdate=?', $fromdate)
                ->where('line_order=?', $line_order);
        $dailyDealId = 0;
        foreach ($dailyDeal as $dd) {
            $dailyDealId = $dd->getRowid();
        }
        if ($dailyDealId != 0) {
            try {
                $dailyDeal = Mage::getModel('homepage/stealday')->load($dailyDealId)
                        ->setidproduct($idproduct)
                        ->setPromotionDesc($promotion_desc)
                        ->setIdtbproduct($idtbproduct)
                        ->setPromotionPrice($promotion_price)
                        ->setFromdate($fromdate)
                        ->setTodate($todate)
						->setWasPrice($was_price)
                        ->setLineOrder($line_order)
                        ->setMaxQty($max_qty)
						->setExtraDescription($extra_description)
                        ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('update stealday', $e->getMessage());
                return 0;
            }
        } else { //else insert new
            try {
                $dailyDeal = Mage::getModel('homepage/stealday')
                        ->setidproduct($idproduct)
                        ->setPromotionDesc($promotion_desc)
                        ->setIdtbproduct($idtbproduct)
                        ->setPromotionPrice($promotion_price)
                        ->setFromdate($fromdate)
                        ->setTodate($todate)
						->setWasPrice($was_price)
                        ->setLineOrder($line_order)
                        ->setMaxQty($max_qty)
						->setExtraDescription($extra_description)
                        ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('insert stealday', $e->getMessage());
                return 0;
            }
        }
        return 1;
    }

    public function updateCrossSaleGroup($cross_sale_group) {
        $idcsgroup = $cross_sale_group->idcsgroup;
        $csgroupname = $cross_sale_group->csgroupname;
        $csentrydate = $cross_sale_group->csentrydate;

        //update database, compare idcsgroup, 
        $dateCrossSaleGroup = Mage::getModel('addons/csgroup')->load($idcsgroup);
        if ($dateCrossSaleGroup->hasData()) {
            //if exist then return idcsgroup 
            try {
                $dateCrossSaleGroup->setCsgroupname($csgroupname)->setCsentrydate($csentrydate)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('CrossSale Group', $e->getMessage());
                return 0;
            }
        } else { //else insert into database then return idcsgroup
            try {
                Mage::getModel('addons/csgroup')->setIdcsgroup($idcsgroup)->setCsgroupname($csgroupname)->setCsentrydate($csentrydate)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('CrossSale Group', $e->getMessage());
                return 0;
            }
        }

        return $idcsgroup; //->getCsgroupname();
    }

    public function updateCrossSaleGroupProduct($cross_sale_group_product) {
        $idcsgroup = $cross_sale_group_product->idcsgroup;
        $crosssaleproducts = $this->initialArray($cross_sale_group_product->crosssaleproducts);
        $csProducts = array();
        $csProductsId = array();
        foreach ($crosssaleproducts as $key => $csproduct) {
            $idcsproduct = $csproduct->idcsproduct;
            $discount = $csproduct->discount;
            $displayname = $csproduct->displayname;
            $sortby = $csproduct->sortby;
            $rowid = $csproduct->rowid;
            $sku = $csproduct->sku;
            $csProductsId[$idcsproduct] = $rowid;
            $csProducts[$rowid] = array("idcsproduct" => $idcsproduct, "discount" => $discount,
                "displayname" => $displayname, "sortby" => $sortby, "rowid" => $rowid, "sku" => $sku);
        }
        $csGroupProduct = Mage::getModel('addons/csgroupproduct')->getCollection()->addFilter("idcsgroup", $idcsgroup);
        $csGroupProductArray = array();
        foreach ($csGroupProduct as $_item) {
            $csGroupProductArray[$_item->getIdcsproduct()] = $_item->getRowid();
        }
        $addCsProducts = array_diff($csProductsId, $csGroupProductArray);
        $delCsProducts = array_diff($csGroupProductArray, $csProductsId);
        if (count($addCsProducts)) {
            foreach ($addCsProducts as $addCsProduct) {//else insert into database then return rowid
                try {
                    Mage::getModel('addons/csgroupproduct')->setRowid($csProducts[$addCsProduct]["rowid"])
                            ->setIdcsproduct($csProducts[$addCsProduct]["idcsproduct"])
                            ->setIdcsgroup($idcsgroup)
                            ->setDiscount($csProducts[$addCsProduct]["discount"])
                            ->setDisplayname($csProducts[$addCsProduct]["displayname"])
                            ->setSortby($csProducts[$addCsProduct]["sortby"])
                            ->setSku($csProducts[$addCsProduct]["sku"])
                            ->save();
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('addCsProducts', $e->getMessage());
                    return 0;
                }
            }
        }
        if (count($delCsProducts)) {
            foreach ($delCsProducts as $delCsProduct) {
                try {
                    Mage::getModel('addons/csgroupproduct')->load($delCsProduct)->delete();
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('delCsProduct', $e->getMessage());
                    return 0;
                }
            }
        }
        return $idcsgroup;
    }

    public function updateCrossSaleProductMap($cross_sale_product_map) {
        $idproduct = $cross_sale_product_map->idproduct;
        $csgroups = $this->initialArray($cross_sale_product_map->csgroups);
        $csProductMaps = array();
        foreach ($csgroups as $key => $csgroup) {
            $idcsgroup = $csgroup->idcsgroup;
            $sortby = $csgroup->sortby;
            $csProductMaps[$key] = array($idproduct, $idcsgroup, $sortby);
        }
        //update database, compare idproduct and idcsgroup 
        foreach ($csProductMaps as $key => $csProductMap) {
            $csGroupProducts = Mage::getModel('addons/csproductmap')->getCollection()
                            ->addFilter("idproduct", $csProductMap[0])->addFilter("idcsgroup", $csProductMap[1]);
            //else insert into database by loop csgroups 
            if ($csGroupProducts->count() == 0) {
                try {
                    Mage::getModel('addons/csproductmap')
                            ->setIdproduct($csProductMap[0])
                            ->setIdcsgroup($csProductMap[1])
                            ->setSortby($csProductMap[2])
                            ->save();
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('CrossSale', $e->getMessage());
                    return 0;
                }
            } else {//if exist then return idproduct 
                foreach ($csGroupProducts as $csGroupProduct) {
                    try {
                        Mage::getModel('addons/csproductmap')->load($csGroupProduct->getRowid())->delete();
                    } catch (Mage_Core_Exception $e) {
                        $this->_fault('CrossSale ProductMap', $e->getMessage());
                        return 0;
                    }
                }
                try {
                    Mage::getModel('addons/csproductmap')
                            ->setIdproduct($csProductMap[0])
                            ->setIdcsgroup($csProductMap[1])
                            ->setSortby($csProductMap[2])
                            ->save();
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('CrossSale ProductMap', $e->getMessage());
                    return 0;
                }
            }
        }
        return $idproduct;
    }

    function getMessage() {
        $idstore = 99;
        $result = array();
        $messageModel = Mage::getModel('customercare/pccomments')->getCollection();
        $messageModel->getSelect()->where('pccomm_idfeedback IS NULL')
                ->where('idstore=?', $idstore);

        foreach ($messageModel as $_item) {
            $result_unit = array();
            $result_unit['idpccomments'] = $_item->getIdpccomments();
            $result_unit['pccomm_idfeedback'] = $_item->getPccommIdfeedback();
            $result_unit['pccomm_idorder'] = $_item->getPccommIdorder();
            $result_unit['pccomm_idmagorder'] = $_item->getPccommIdmagorder();
            $result_unit['pccomm_idparent'] = $_item->getPccommIdparent();
            $result_unit['pccomm_idmagparent'] = $_item->getPccommIdmagparent();
            $result_unit['pccomm_iduser'] = $_item->getPccommIduser();
            $result_unit['pccomm_idmaguser'] = $_item->getPccommIdmaguser();
            $result_unit['pccomm_createddate'] = $_item->getPccommCreateddate();
            $result_unit['pccomm_editeddate'] = $_item->getPccommEditeddate();
            $result_unit['pccomm_ftype'] = $_item->getPccommFtype();
            $result_unit['pccomm_fstatus'] = $_item->getPccommFstatus();
            $result_unit['pccomm_priority'] = $_item->getPccommPriority();
            $result_unit['pccomm_description'] = $_item->getPccommDescription();
            $result_unit['pccomm_details'] = $_item->getPccommDetails();
            $result_unit['idproduct'] = $_item->getIdproduct();
            $result_unit['idmagproduct'] = $_item->getIdmagproduct();
            $result_unit['pccomm_internalnotes'] = $_item->getPccommInternalnotes();
            $result_unit['notesreaded'] = $_item->getNotesreaded();
            $result_unit['notedate'] = $_item->getNotedate();
            $result_unit['pccomm_idproduct'] = $_item->getPccommIdproduct();
            $result_unit['pccomm_productdes'] = $_item->getPccommProductdes();
            $result_unit['pccomm_keeplive'] = $_item->getPccommKeeplive();
            $result_unit['sourcetype'] = $_item->getSourcetype();
            $result_unit['last_staff_id'] = $_item->getLastStaffId();
            $result_unit['idstore'] = $_item->getIdstore();
            //push into $result
            array_push($result, $result_unit);
        }
        //return most recent message list / 
        //type is tbMessageArray , defind in wsi.xml
        return $result;
    }

    function syncMessage($message) {
		if (isset($message))
		{
			$idpccomments = $message->idpccomments;
			$pccomm_idfeedback = $message->pccomm_idfeedback;
			$pccomm_idorder = $message->pccomm_idorder;
			$pccomm_idmagorder = $message->pccomm_idmagorder;
			$pccomm_idparent = $message->pccomm_idparent;
			$pccomm_idmagparent = $message->pccomm_idmagparent;
			$pccomm_iduser = $message->pccomm_iduser;
			$pccomm_idmaguser = $message->pccomm_idmaguser;
			$pccomm_createddate = $message->pccomm_createddate;
			$pccomm_editeddate = $message->pccomm_editeddate;
			$pccomm_ftype = $message->pccomm_ftype;
			$pccomm_fstatus = $message->pccomm_fstatus;
			$pccomm_priority = $message->pccomm_priority;
			$pccomm_description = $message->pccomm_description;
			$pccomm_details = $message->pccomm_details;
			$idproduct = $message->idproduct;
			$idmagproduct = $message->idmagproduct;
			$pccomm_internalnotes = $message->pccomm_internalnotes;
			$notesreaded = $message->notesreaded;
			$notedate = $message->notedate;
			$pccomm_idproduct = $message->pccomm_idproduct;
			$pccomm_productdes = $message->pccomm_productdes;
			$pccomm_keeplive = $message->pccomm_keeplive;
			$sourcetype = $message->sourcetype;
			$last_staff_id = $message->last_staff_id;
			$idstore = $message->idstore;
			if (!$idpccomments) {
				$messageByTPK = Mage::getModel('customercare/pccomments')->getCollection();
				$messageByTPK->getSelect()->where('pccomm_idfeedback=?', $pccomm_idfeedback);
				foreach ($messageByTPK as $_item) {
					$idpccomments = $_item->getIdpccomments();
				}
			}
			$messageByPK = Mage::getModel('customercare/pccomments')->load($idpccomments);
	
			if ($messageByPK->hasData()) {
				try {
					$messageByPK->setIdpccomments($idpccomments)->setPccommIdfeedback($pccomm_idfeedback)->setPccommIdorder($pccomm_idorder)
							->setPccommIdmagorder($pccomm_idmagorder)->setPccomm_idparent($pccomm_idparent)->setPccommIdmagparent($pccomm_idmagparent)
							->setPccommIduser($pccomm_iduser)->setPccommIdmaguser($pccomm_idmaguser)->setPccommCreateddate($pccomm_createddate)
							->setPccommEditeddate($pccomm_editeddate)->setPccommFtype($pccomm_ftype)->setPccommFstatus($pccomm_fstatus)
							->setPccommPriority($pccomm_priority)->setPccommDescription($pccomm_description)->setPccommDetails($pccomm_details)
							->setIdproduct($idproduct)->setIdmagproduct($idmagproduct)->setPccommInternalnotes($pccomm_internalnotes)
							->setNotesreaded($notesreaded)->setNotedate($notedate)->setPccommIdproduct($pccomm_idproduct)
							->setPccommProductdes($pccomm_productdes)->setPccommKeeplive($pccomm_keeplive)->setSourcetype($sourcetype)
							->setLastStaffId($last_staff_id)->setIdstore($idstore)
							->save();
				} catch (Mage_Core_Exception $e) {
					$this->_fault('syncMessage update', $e->getMessage());
					$idpccomments = 0;
					$pccomm_idfeedback = 0;
				}
			} else {
				try {
					$messageNew = Mage::getModel('customercare/pccomments');
					$messageNew->setPccommIdfeedback($pccomm_idfeedback)->setPccommIdorder($pccomm_idorder)
							->setPccommIdmagorder($pccomm_idmagorder)->setPccomm_idparent($pccomm_idparent)->setPccommIdmagparent($pccomm_idmagparent)
							->setPccommIduser($pccomm_iduser)->setPccommIdmaguser($pccomm_idmaguser)->setPccommCreateddate($pccomm_createddate)
							->setPccommEditeddate($pccomm_editeddate)->setPccommFtype($pccomm_ftype)->setPccommFstatus($pccomm_fstatus)
							->setPccommPriority($pccomm_priority)->setPccommDescription($pccomm_description)->setPccommDetails($pccomm_details)
							->setIdproduct($idproduct)->setIdmagproduct($idmagproduct)->setPccommInternalnotes($pccomm_internalnotes)
							->setNotesreaded($notesreaded)->setNotedate($notedate)->setPccommIdproduct($pccomm_idproduct)
							->setPccommProductdes($pccomm_productdes)->setPccommKeeplive($pccomm_keeplive)->setSourcetype($sourcetype)
							->setLastStaffId($last_staff_id)->setIdstore($idstore)
							->save();
					$idpccomments = $messageNew->getIdpccomments();
				} catch (Mage_Core_Exception $e) {
					$this->_fault('syncMessage insert', $e->getMessage());
					$idpccomments = 0;
					$pccomm_idfeedback = 0;
				}
			}
			return $idpccomments . "," . $pccomm_idfeedback . ";";
		}
        //return json string for map between between mag and top
        //return format    idpccomments.",".pccomm_idfeedback.";" 
    }

    function syncMessageStatus($messageStatus) {
        $pcfstat_idstatus = $messageStatus->pc_fstat_id_status;
        $pcfstat_name = $messageStatus->pc_fstat_name;
        $pcfstat_img = $messageStatus->pc_fstat_img;
        $pcfstat_bgcolor = $messageStatus->pc_fstat_bgcolor;
        $pcfstat_showimg = $messageStatus->pc_fstat_show_img;
        //update database, compare $pcpri_idpri, 
        $messageStatusModel = Mage::getModel('customercare/pcfstatus')->load($pcfstat_idstatus);
        if ($messageStatusModel->hasData()) {
            try {
                $messageStatusModel->setPcfstatIdstatus($pcfstat_idstatus)->setPcfstatName($pcfstat_name)
                        ->setPcfstatImg($pcfstat_img)->setPcfstatBgcolor($pcfstat_bgcolor)->setPcfstatShowimg($pcfstat_showimg)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncMessageStatus update', $e->getMessage());
                return false;
            }
        } else {
            try {
                Mage::getModel('customercare/pcfstatus')->setPcfstatIdstatus($pcfstat_idstatus)->setPcfstatName($pcfstat_name)->setPcfstatImg($pcfstat_img)
                        ->setPcfstatBgcolor($pcfstat_bgcolor)->setPcfstatShowimg($pcfstat_showimg)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncMessageStatus insert', $e->getMessage());
                return false;
            }
        }
        return true;
    }

    function syncMessagePriority($messagePriority) {
        $pcpri_idpri = $messagePriority->pc_pri_id_pri;
        $pcpri_name = $messagePriority->pc_pri_name;
        $pcpri_img = $messagePriority->pc_pri_img;
        $pcpri_showimg = $messagePriority->pc_pri_show_img;
        //update database, compare $pcpri_idpri, 
        $messagePriorityModel = Mage::getModel('customercare/pcpriority')->load($pcpri_idpri);
        if ($messagePriorityModel->hasData()) {
            try {
                $messagePriorityModel->setPcpriIdpri($pcpri_idpri)->setPcpriName($pcpri_name)
                        ->setPcpriImg($pcpri_img)->setPcpriShowimg($pcpri_showimg)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncMessagePriority update', $e->getMessage());
                return false;
            }
        } else {
            try {
                Mage::getModel('customercare/pcpriority')->setPcpriIdpri($pcpri_idpri)->setPcpriName($pcpri_name)
                        ->setPcpriImg($pcpri_img)->setPcpriShowimg($pcpri_showimg)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncMessagePriority insert', $e->getMessage());
                return false;
            }
        }
        return true;
    }

    function syncMessageType($messageType) {
        $pcftype_idtype = $messageType->pc_ftype_id_type;
        $pcftype_name = $messageType->pc_ftype_name;
        $pcftype_img = $messageType->pc_ftype_img;
        $pcftype_showimg = $messageType->pc_ftype_show_img;
        $manager = $messageType->manager;
        $displaytype = $messageType->display_type;
        $sortby = $messageType->sort_by;
        //update database, compare $pcpri_idpri, 
        $messageTypeModel = Mage::getModel('customercare/pcftypes')->load($pcftype_idtype);
        if ($messageTypeModel->hasData()) {
            try {
                $messageTypeModel->setPcftypeIdtype($pcftype_idtype)
                        ->setPcftypeName($pcftype_name)->setPcftypeImg($pcftype_img)->setPcftypeShowimg($pcftype_showimg)
                        ->setManager($manager)->setDisplaytype($displaytype)->setSortby($sortby)
                        ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncMessageType update', $e->getMessage());
                return false;
            }
        } else {
            try {
                Mage::getModel('customercare/pcftypes')->setPcftypeIdtype($pcftype_idtype)
                        ->setPcftypeName($pcftype_name)->setPcftypeImg($pcftype_img)->setPcftypeShowimg($pcftype_showimg)
                        ->setManager($manager)->setDisplaytype($displaytype)->setSortby($sortby)
                        ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncMessageType insert', $e->getMessage());
                return false;
            }
        }
        return true;
    }

    function getMessageRank() {
        //please use same way as getMessage()
        $result = array();
        $messageRank = Mage::getModel('customercare/pccommentsrank')->getCollection();
        $messageRank->getSelect()->where('updatedate>?', date('Y-m-d h:i:s', time() - (3600 * 3)));
        foreach ($messageRank as $_item) {
            $result_unit = array();
            $result_unit['rank_mag_id'] = $_item->getRankMagId();
            $result_unit['id_mag_pccomments'] = $_item->getIdMagPccomments();
            $result_unit['rank_type'] = $_item->getRankType();
            $result_unit['rank_point'] = $_item->getRankPoint();
            array_push($result, $result_unit);
        }
        return $result;
    }

    function syncNewsletter($newsletter) {
        $newsletterheadid = $newsletter->newsletter_head_id;
        $senddate = $newsletter->send_date;
        $newssubject = $newsletter->subject_line;
        $newsletterModel = Mage::getModel('newsletternotify/newsletterheader')->load($newsletterheadid);
        if ($newsletterModel->hasData()) {
            try {
                $newsletterModel->setNewsletterheadid($newsletterheadid)->setSenddate($senddate)
                        ->setNewssubject($newssubject)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncNewsletter update', $e->getMessage());
                return false;
            }
        } else {
            try {
                Mage::getModel('newsletternotify/newsletterheader')->setNewsletterheadid($newsletterheadid)->setSenddate($senddate)
                        ->setNewssubject($newssubject)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncNewsletter insert', $e->getMessage());
                return false;
            }
        }
        return true;
    }

    function syncCategoryMap($_categorymap) {
        $id_tbcategory = $_categorymap->id_tbcategory;
        $id_magcategory = $_categorymap->id_magcategory;
        $categorymapModel = Mage::getModel('homepage/categorymap')->getCollection()->addFilter('id_magcategory', $id_magcategory)->getFirstItem();
        if (!$categorymapModel->hasData()) {
            try {
                Mage::getModel('homepage/categorymap')->setIdTbcategory($id_tbcategory)
                        ->setIdMagcategory($id_magcategory)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncCategoryMap insert', $e->getMessage());
                return false;
            }
        } else {
            try {
                $categorymapModel->setIdTbcategory($id_tbcategory)
                        ->setIdMagcategory($id_magcategory)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syncCategoryMap update', $e->getMessage());
                return false;
            }
        }
        return true;
    }

    function syncCustomerMap($customerMap) {
        $id_tbcustomer = $customerMap->id_tbcustomer;
        $id_magcustomer = $customerMap->id_magcustomer;
		if ($id_tbcustomer != 0 && $id_magcustomer != 0)
		{
			$customerMapModel = Mage::getModel('homepage/customermap')->load($id_tbcustomer);
			if (!$customerMapModel->hasData()) {
				try {
					Mage::getModel('homepage/customermap')->setIdTbcustomer($id_tbcustomer)
							->setIdMagcustomer($id_magcustomer)->save();
					$customerModel = Mage::getModel('customer/customer')->load($id_magcustomer);
					if ($customerModel->hasData()) {
						$customerModel->setTbcustomerid($id_tbcustomer)->save();
					}
				} catch (Mage_Core_Exception $e) {
					$this->_fault('syncCustomerMap insert', $e->getMessage());
					return false;
				}
			}
			return true;
		}
		else
		{
			return false;
			}
        
    }

    function syncOrderMap($orderMap) {
        $id_tborder = $orderMap->id_tborder;
        $id_magorder = $orderMap->id_magorder;
        $orderMapModel = Mage::getModel('homepage/ordermap')->load($id_tborder);
        if (!$orderMapModel->hasData()) {
            try {
                Mage::getModel('homepage/ordermap')->setIdTborder($id_tborder)
                        ->setIdMagorder($id_magorder)->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('syndOrderMap insert', $e->getMessage());
                return false;
            }
        }
        return true;
    }

    function updateCategoryExtraInfor($categories_extra_infor) {
        $id_tbcategory = $categories_extra_infor->id_tbcategory;
        $id_magcategory = $categories_extra_infor->id_magcategory;
        $supplier = $categories_extra_infor->supplier;
        $extra_infor1 = $categories_extra_infor->extra_infor1;
        $extra_infor2 = $categories_extra_infor->extra_infor2;
        $extra_infor3 = $categories_extra_infor->extra_infor3;

        $categorymapModel = Mage::getModel('homepage/categorymap')->getCollection()->addFilter('id_tbcategory', $id_tbcategory);
        foreach ($categorymapModel as $idcategory) {
            $prddescModel = Mage::getModel('ajax/prddescription')->loadByCategory($idcategory->getIdMagcategory())->getFirstItem();
            if ($prddescModel->hasData()) {
                try {
                    $prddescModel
                            ->setSupplierid($supplier)
                            ->setCatalogid($idcategory->getIdMagcategory())
                            ->setDescription1($extra_infor1)
                            ->setDescription2($extra_infor2)
                            ->setDescription3($extra_infor3)
                            ->save();
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('updateCategoryExtraInfor update', $e->getMessage());
                    return false;
                }
            } else {
                try {
                    Mage::getModel('ajax/prddescription')
                            ->setSupplierid($supplier)
                            ->setCatalogid($idcategory->getIdMagcategory())
                            ->setDescription1($extra_infor1)
                            ->setDescription2($extra_infor2)
                            ->setDescription3($extra_infor3)
                            ->save();
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('updateCategoryExtraInfor insert', $e->getMessage());
                    return false;
                }
            }
        }
        return true;
    }

    function updateOrderHistory($orders_history) {
        $id_tborder = $orders_history->id_tborder;
        $orderdate = $orders_history->orderdate;
        $id_magcustomer = $orders_history->id_magcustomer;
        $id_tbcustomer = $orders_history->id_tbcustomer;
        $token_key = $orders_history->token_key;
		$shipping_fullname = $orders_history->shipping_fullname;
		$orderamount = $orders_history->orderamount; 
		
        $orderHistoryModel = Mage::getModel('homepage/ordershistory')->getCollection()->addFilter('id_tborder', $id_tborder)->getFirstItem();
        if ($orderHistoryModel->hasData()) {
            try {
                $orderHistoryModel
                        ->setIdTborder($id_tborder)
                        ->setOrderdate($orderdate)
                        ->setIdMagcustomer($id_magcustomer)
                        ->setIdTbcustomer($id_tbcustomer)
                        ->setTokenKey($token_key)
						->setShippingFullname($shipping_fullname)
						->setOrderamount($orderamount)
                        ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('updateOrderHistory update', $e->getMessage());
                return false;
            }
        } else {
            try {
                Mage::getModel('homepage/ordershistory')
                        ->setIdTborder($id_tborder)
                        ->setOrderdate($orderdate)
                        ->setIdMagcustomer($id_magcustomer)
                        ->setIdTbcustomer($id_tbcustomer)
                        ->setTokenKey($token_key)
						->setShippingFullname($shipping_fullname)
						->setOrderamount($orderamount)
                        ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('updateOrderHistory insert', $e->getMessage());
                return false;
            }
        }
        return true;
    }

    function ProductUpdates($product) {
		//update inactive product list
//        $idmagproduct = 11;
//        $idtbproduct = 22;
//        $productname = 'productname';
//        $weight = 2;
//        $price = 33;
//        $listprice = 44;
//        $stock = 12;
//        $is_in_stock = 1;
//        $freeshipping = 1;
//        $fixshippingfee = 2;
//        $capshippingfee = 3;
//        $shippingtype = 1;
        $idmagproduct = $product->idproduct;
        $idtbproduct = $product->idtbproduct;
        $productname = $product->productname;
		$productdescription = $product->productdescription;
		
		if(!isset($productname))
		{
			$productname = "";
			}
		if(!isset($productdescription))
		{
			$productdescription = "";
			}
        $weight = $product->weight;
        $price = $product->price;
        $listprice = $product->listprice;
        $stock = $product->stock;
        $is_in_stock = $product->is_in_stock;
        $freeshipping = $product->freeshipping;
        $fixshippingfee = $product->fixshippingfee;
        $capshippingfee = $product->capshippingfee;
        $shippingtype = $product->shippingtype;
        //insert into record, if found then update and set time stamp
        $productUpdateModel = Mage::getModel('homepage/dailyproductupdate')->getCollection()->addFilter('idtbproduct', $idtbproduct)->getFirstItem();
        if ($productUpdateModel->hasData()) {
            try {
                $productUpdateModel
                        ->setIdmagproduct($idmagproduct)
                        ->setIdtbproduct($idtbproduct)
                        ->setWeight($weight)
                        ->setPrice($price)
                        ->setListprice($listprice)
                        ->setStock($stock)
                        ->setIsInStock($is_in_stock)
                        ->setFreeshipping($freeshipping)
                        ->setFixshippingfee($fixshippingfee)
                        ->setCapshippingfee($capshippingfee)
                        ->setShippingtype($shippingtype); 
			   if($productname != "")
			   {
				    $productUpdateModel->setName($productname); 
				   }
			   if($productdescription != "")
			   {
				    $productUpdateModel->setDescription($productdescription);
				   }
			       
               $productUpdateModel->save();
			   
            } catch (Mage_Core_Exception $e) {
                $this->_fault('ProductUpdates update', $e->getMessage());
                return 0;
            }
        } else {
            try {
                 $productNewModel = Mage::getModel('homepage/dailyproductupdate');
                 $productNewModel->setIdmagproduct($idmagproduct)
                        ->setIdtbproduct($idtbproduct)
                        ->setProductname($productname)
                        ->setWeight($weight)
                        ->setPrice($price)
                        ->setListprice($listprice)
                        ->setStock($stock)
                        ->setIsInStock($is_in_stock)
                        ->setFreeshipping($freeshipping)
                        ->setFixshippingfee($fixshippingfee)
                        ->setCapshippingfee($capshippingfee)
                        ->setShippingtype($shippingtype);                        
						
			   if($productname != "")
			   {
				    $productNewModel->setName($productname);
				   }
			   if($productdescription != "")
			   {
				    $productNewModel->setDescription($productdescription);
				   }
			 
			   $productNewModel->save();
                        
            } catch (Mage_Core_Exception $e) {
                $this->_fault('ProductUpdates insert', $e->getMessage());
                return 0;
            }
        }
        return $idtbproduct;
    }

    function CrossSaleProduct($cross_sale_record) {
        $rowid = $cross_sale_record->rowid;
        $idtbproduct_primary = $cross_sale_record->idtbproduct_primary;
        $idtbproduct_discount = $cross_sale_record->idtbproduct_discount;
        $idmagproduct_primary = $cross_sale_record->idmagproduct_primary;
        $idmagproduct_discount = $cross_sale_record->idmagproduct_discount;
        $fromdate = $cross_sale_record->fromdate;
        $todate = $cross_sale_record->todate;
        $qty_limit = $cross_sale_record->qty_limit;
        $discount_desc = $cross_sale_record->discount_desc;
        $discount_type = $cross_sale_record->discount_type;
        $discount_value = $cross_sale_record->discount_value;
        $discount_flag = $cross_sale_record->discount_flag;
//        $rowid = 2;
//        $idtbproduct_primary = 222;
//        $idtbproduct_discount = 333;
//        $idmagproduct_primary = 444;
//        $idmagproduct_discount = 555;
//        $fromdate = '2012-06-11 11:11:11';
//        $todate =  '2012-06-15 11:11:11';
//        $qty_limit = 12;
//        $discount_desc = 33;
//        $discount_type = 11;
//        $discount_value = 66;
//        $discount_flag = 2;
        //insert into record, if found then update and set time stamp
        $crossSaleModel = Mage::getModel('homepage/promotioncrosssale')->getCollection()->addFilter('rowid', $rowid)->getFirstItem();
        if ($crossSaleModel->hasData()) {
            try {
                $crossSaleModel
                        ->setRowid($rowid)
                        ->setIdtbproductPrimary($idtbproduct_primary)
                        ->setIdtbproductDiscount($idtbproduct_discount)
                        ->setIdmagproductPrimary($idmagproduct_primary)
                        ->setIdmagproductDiscount($idmagproduct_discount)
                        ->setFromdate($fromdate)
                        ->setTodate($todate)
                        ->setQtyLimit($qty_limit)
                        ->setDiscountDesc($discount_desc)
                        ->setDiscountType($discount_type)
                        ->setDiscountValue($discount_value)
                        ->setDiscountFlag($discount_flag)
                        ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('CrossSaleProduct update', $e->getMessage());
                return 0;
            }
        } else {
            try {
                Mage::getModel('homepage/promotioncrosssale')
                        ->setRowid($rowid)
                        ->setIdtbproductPrimary($idtbproduct_primary)
                        ->setIdtbproductDiscount($idtbproduct_discount)
                        ->setIdmagproductPrimary($idmagproduct_primary)
                        ->setIdmagproductDiscount($idmagproduct_discount)
                        ->setFromdate($fromdate)
                        ->setTodate($todate)
                        ->setQtyLimit($qty_limit)
                        ->setDiscountDesc($discount_desc)
                        ->setDiscountType($discount_type)
                        ->setDiscountValue($discount_value)
                        ->setDiscountFlag($discount_flag)
                        ->save();
            } catch (Mage_Core_Exception $e) {
                $this->_fault('CrossSaleProduct insert', $e->getMessage());
                return 0;
            }
        }
        return $rowid;
    }
 
 
    //jack. must add tb_review_id to review table
    public function ProductReview($productreview)
    {
        $idproductReviewTB = $productreview->idproductReviewTB;
        $idproductReviewMag = $productreview->idproductReviewMag;
        $idproduct = $productreview->idproduct;
        $idtbproduct = $productreview->idtbproduct;
        $idcustomer = $productreview->idcustomer;
        $idtbcustomer = $productreview->idtbcustomer;
        $entrydate = $productreview->entrydate;
        $status = $productreview->status;
        $ranks = $productreview->ranks;
        $nickname = $productreview->nickname;
        $review_content = $productreview->review_content;
        
        $ranksArray = array();
        foreach ($this->initialArray($ranks) as $rank){
            $ranksArray[$rank->idrank]=$rank->rankscore;
            if ($rank->rankscore==0) {
                $ranksArray[$rank->idrank]=4;
            }
        }
//        $idproductReviewMag = 130;
//        $idproductReviewTB = 155;
//        $idproduct = 1555;
//        $idcustomer = 2;
//        $entrydate = '2012-06-11 11:11:11';
//        $nickname = 'jack';
//        $status=2;
//        $review_content = 'g';
//        $ranksArray=array(1=>4,2=>3,3=>1,4=>5);
        $ranksArray[2]=(int)$ranksArray[2]+5;
        $ranksArray[3]=(int)$ranksArray[3]+10;
        $ranksArray[4]=(int)$ranksArray[4]+15;
        $ranksArray[5]=(int)$ranksArray[5]+20;
        if ($idproductReviewMag){
            try {
                $review = Mage::getModel('review/review')->load($idproductReviewMag);
                $review->setEntityId($review->getEntityIdByCode('product'))
                    ->setEntityPkValue($idproduct)
                    ->setStatusId($status)
                    ->setNickname($nickname)
                    ->setTitle(substr($review_content,0,200))
                    ->setDetail($review_content)
                    ->setCustomerId($idcustomer)
                    ->setStoreId(1)
                    ->setStores(array(1))
//                    ->setTbReviewId($idproductReviewTB)
                    ->setCreatedAt($entrydate)
                    ->save();
                return $review->getId().','.$idproductReviewTB;
            } catch (Mage_Core_Exception $e) {
                $this->_fault('ProductUpdates update', $e->getMessage());
                return '0,'.$idproductReviewTB;
            }
        } else {
            try {
                $review = Mage::getModel('review/review');
                $review->setEntityId($review->getEntityIdByCode('product'))
                    ->setEntityPkValue($idproduct)
                    ->setStatusId($status)
                    ->setNickname($nickname)
                    ->setTitle(substr($review_content,0,200))
                    ->setDetail($review_content)
                    ->setCustomerId($idcustomer)
                    ->setStoreId(1)
                    ->setStores(array(1))
//                    ->setTbReviewId($idproductReviewTB)
                    ->setCreatedAt($entrydate)
                    ->save();

                foreach ($ranksArray as $key => $rank) {
                    Mage::getModel('rating/rating')
                    ->setRatingId($key)
                    ->setReviewId($review->getId())
                    ->setCustomerId($idcustomer)
                    ->addOptionVote($rank, $idproduct);
                }
                $review->aggregate();
                return $review->getId().','.$idproductReviewTB;
            } catch (Mage_Core_Exception $e) {
                $this->_fault('ProductUpdates update', $e->getMessage());
                return '0,'.$idproductReviewTB;
            }
        }
        //return true / false
    }

    function ProductReviewFromMag() {
        $reviews = Mage::getModel('review/review')->getCollection()
                ->addStatusFilter(2)
//                ->addFieldToFilter('created_at', array('date'=>true, 'from'=> date('Y-m-d h:i:s', time() - (3600 * 3))))
                ->setDateOrder()
                ->load()
                ->addRateVotes();
        
        $reviewsArray = array();
        foreach ($reviews->getItems() as $review){
            $customerTbId = (int)Mage::getModel('homepage/customermap')->getCollection()->addFilter('id_magcustomer', $review->getCustomerId())->getFirstItem()->getIdTbcustomer();
            $productTbId = (int)Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('entity_id')->addAttributeToFilter('idtbproduct', $review->getEntityPkValue())->getFirstItem()->getId(); 
            if ($customerTbId&&$productTbId){
                $ranksArray = array();
                foreach ($review->getRatingVotes() as $vote){
                    $ranksArray[$vote->getRatingId()]=$vote->getValue();
                }
                $reviewArray = array('idproductReviewMag'=>$review->getId(),
                                    'entrydate'=>$review->getCreatedAt(),
                                    'idtbproduct'=>$productTbId,
                                    'idtbcustomer'=>$customerTbId,
                                    'nickname'=>$review->getNickname(),
                                    'review_content'=>$review->getDetail(),
                                    'ranks'=>$ranksArray
                                    );
                array_push($reviewsArray, $reviewArray);
            }
        }
        return ($reviewsArray);
        //return array of new reviews within 3 hour
        //Type: tbProductReviewArray
        //only retrieve idtbcustomer map and idtbproduct map are sync record

    } 

	//function used to reindex product seperatly via Database Call
	//pass in idproduct
	// 
	function ReindexProductStockFromDB($productId, $stock, $status)
	{
		$product = Mage::getModel('catalog/product')->load($productId);
		if (isset($product))
		{ 
			try{ 
			/*
			 	$write = Mage::getSingleton('core/resource')->getConnection('core_write');  
				$write->query("insert into tablename values ('aaa','bbb','ccc')");  
			*/
			$read = Mage::getSingleton('core/resource')->getConnection('extension_read'); 
			$query = $read->query("select * from cataloginventory_stock_status where product_id = ".$productId); 
			$counter = 0;
			while ($row = $query->fetch())   
			{   
			 	$counter = $counter + 1;
			}  
			if ($counter > 0)
			{
			 
				//update stock table
				//start to write database 
				$write = Mage::getSingleton('core/resource')->getConnection('core_write');  
				$write->query("update cataloginventory_stock_status set qty = ".$stock." , stock_status = ".$status." where product_id = ".$productId."");  
				return 2;
				}
			 else
			 {
				//start to write database 
				$write = Mage::getSingleton('core/resource')->getConnection('core_write');  
				$write->query("insert into cataloginventory_stock_status (product_id, website_id, stock_id, qty, stock_status) values (".$productId.", 1,1 ,".$stock.",".$status.");");     			return 1;
			  }
				
			} catch (Mage_Core_Exception $e) {
                $this->_fault('Reindex Stock Failed for product ID: '.$productId, $e->getMessage()); 
				return 0;
            } 
		} 
	}

	function ReindexProductPriceFromDB($productId)
	{
		$product = Mage::getModel('catalog/product')->load($productId);
		if (isset($product))
		{ 
			try{ 
				/*
					$write = Mage::getSingleton('core/resource')->getConnection('core_write');  
					$write->query("insert into tablename values ('aaa','bbb','ccc')");  
				*/ 
				$tax_class_id = $product->getTaxClassId();
				$price = $product->getPrice();
			 
				$read = Mage::getSingleton('core/resource')->getConnection('extension_read'); 
				$query = $read->query("select * from catalog_product_index_price where entity_id = ".$productId); 
				$counter = 0;
				while ($row = $query->fetch())   
				{   
					$counter = $counter + 1;
				}  
				if ($counter > 0)
				{
					return  0;
				}
				else
				{
					//start to write database 
					$write = Mage::getSingleton('core/resource')->getConnection('core_write');  
					$write->query("insert into catalog_product_index_price (entity_id, customer_group_id, website_id, tax_class_id, price, final_price, min_price, max_price) values (".$productId.", 0,1 ,".$tax_class_id.",".$price.",".$price.",".$price.",".$price.");");     			
					return 1;
				}				
			} catch (Mage_Core_Exception $e) {
                $this->_fault('Reindex Price Failed for product ID: '.$productId, $e->getMessage()); 			 
				return 0;
            } 
		} 
	}

	
	//function used to reindex product seperatly
	//pass in idproduct
	// 
	function ReindexProduct($productId)
	{
		$product = Mage::getModel('catalog/product')->load($productId);
		if (isset($product))
		{ 
			try{ 
			  
				Mage::getSingleton('index/indexer')->processEntityAction(
				$product, Mage_Catalog_Model_Product::ENTITY, Mage_Index_Model_Event::TYPE_SAVE
				);
				Mage::getSingleton('index/indexer')->processEntityAction(
					new Varien_Object(array('id' => $productId)),
					Mage_Catalog_Model_Product::ENTITY,
					Mage_Catalog_Model_Product_Indexer_Price::EVENT_TYPE_REINDEX_PRICE
				);
				 
				$stockItem = Mage::getModel('cataloginventory/stock_item')->load($productId);
				Mage::getSingleton('index/indexer')->processEntityAction(
					$stockItem, Mage_CatalogInventory_Model_Stock_Item::ENTITY, Mage_Index_Model_Event::TYPE_SAVE
				); 
				
				// register mass action indexer event
				Mage::getSingleton('index/indexer')->processEntityAction(
					$product, Mage_Catalog_Model_Product::ENTITY, Mage_Index_Model_Event::TYPE_MASS_ACTION
				);  
				return 1;
			} catch (Mage_Core_Exception $e) {
                $this->_fault('Reindex Failed for product ID: '.$productId, $e->getMessage()); 
				return 0;
            } 
		} 
	}
	
	//function used to reindex whole category index
	function ReindexCategory($categoryId)
	{	 
		$category = Mage::getModel('catalog/category')->load($categoryId);
		if(isset($category))
		{
			try{
					Mage::getSingleton('index/indexer')->processEntityAction($category, Mage_Catalog_Model_Category::ENTITY, Mage_Index_Model_Event::TYPE_SAVE);
				} 
				catch (Mage_Core_Exception $e) {
                	$this->_fault('Reindex Failed for Category ID: '.$categoryId, $e->getMessage()); 
            	} 
		} 
	}
	// function used to reindex all categories relative to product
	function ReindexCategoryProducts($productId)
	{
		$product = Mage::getModel('catalog/product')->load($productId);
		$categoryIds = $product->getCategoryIds();
		foreach($categoryIds as $categoryId)
		{
			$this->ReindexCategoryProduct($categoryId, $productId, $position=0, $isParent=1, $storeId=1, $visibility=4);
		}
	}


    function ReindexProductSQL($productId) {
//        $productId = 6666; //for example
//        $categoryId = 42; //for example
        $product = Mage::getModel('catalog/product')->load($productId);
        $_linkInstance = Mage::getSingleton('catalog/product_link');
        $_linkInstance->saveProductRelations($product);

        $indexerStock = Mage::getModel('cataloginventory/stock_status');
        $indexerStock->updateStatus($productId);

        $indexerPrice = Mage::getResourceModel('catalog/product_indexer_price');
        $indexerPrice->reindexProductIds($productId);
        $this->ReindexCategoryProducts($productId);
    }

    function ReindexCategoryProduct($categoryId, $productId, $position=0, $isParent=1, $storeId=1, $visibility=4) {
//        $product = Mage::getModel('catalog/product')->load($productId);
//        if (isset($product)) {
            try {
                /*
                  $write = Mage::getSingleton('core/resource')->getConnection('core_write');
                  $write->query("insert into tablename values ('aaa','bbb','ccc')");
                 */
                $read = Mage::getSingleton('core/resource')->getConnection('extension_read');
                $query = $read->query("select * from catalog_category_product_index where product_id = $productId and category_id = $categoryId");
                $counter = 0;
                while ($row = $query->fetch()) {
                    $counter = $counter + 1;
                }
                if ($counter > 0) {

                    //update stock table
                    //start to write database 
                    $write = Mage::getSingleton('core/resource')->getConnection('core_write');
                    $write->query("update catalog_category_product_index set category_id = $categoryId, product_id = $productId, `position` = $position, is_parent = $isParent, store_id = $storeId, visibility = $visibility where product_id = $productId and category_id = $categoryId ");
                    return 2;
                } else {
                    //start to write database 
                    $write = Mage::getSingleton('core/resource')->getConnection('core_write');
                    $write->query("insert into catalog_category_product_index (category_id,product_id,`position`,is_parent,store_id,visibility) values ($categoryId, $productId, $position, $isParent, $storeId, $visibility);");
                    return 1;
                }
            } catch (Mage_Core_Exception $e) {
                $this->_fault('Reindex catalog_category_product_index Failed for product ID: ' . $productId, $e->getMessage());
                return 0;
            }
//        }
    }
}

