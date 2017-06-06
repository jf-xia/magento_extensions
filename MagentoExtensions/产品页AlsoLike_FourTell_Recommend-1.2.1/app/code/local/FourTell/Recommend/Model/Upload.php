<?php
/**
 * 
 * 4-Tell Product Recommendations
 * 
 * This is for loading and saving the upload configuration xml file
 * settings from admin/Catalog/4-Tell Recommendations
 * 
 */
class FourTell_Recommend_Model_Upload extends Varien_Object
{
	public $options 					= null;
	public $upload_sales_data 			= "Yes";
	public $upload_replacement_data 	= "Yes";
	public $upload_product_data 		= "Yes";
	public $upload_exclusion_data 		= "Yes";
	public $replace_catalog_old_field 	= null;
	public $replace_catalog_new_field 	= null;
	public $product_attributtes	 		= null;
	public $showUploadDetails			= "No";
	
	
	public function getOptions()
	{
		return $this->options;
	}
	
	
	public function toBoolean($val) 
	{
		if (strtolower($val) == "yes" || $val == 1 || $val === TRUE) {
			return TRUE;
		}
		
		return FALSE;
	}
	
	
	public function getSetting($setting)
	{
		$opts = $this->getOptions();
		
		return $opts[$setting];
	}
	
	
	public function getExcludeOptions()
	{
		$opts = $this->getOptions();
		
		return $opts['exclude_options'];
	}
	
	
	public function setOptions($opts)
	{
		$this->options['exclude_options'] = array();
		if (isset($opts['exclude_options']->option) && count($opts['exclude_options']->option)) {
			foreach ($opts['exclude_options']->option as $opt) {
				$this->options['exclude_options'][] = $opt;
			}
		} else {
			$this->options['exclude_options'] = array();
		}

		$this->options['replace_options'] = array();
		if (isset($opts['replace_options']->option) && count($opts['replace_options']->option)) {
			foreach ($opts['replace_options']->option as $opt) {
				$this->options['replace_options'][] = $opt;
			}
		} else {
			$this->options['replace_options'] = array();
		}

		if (isset($opts['upload_sales_data'])) {
			$this->options['upload_sales_data'] 		= $opts['upload_sales_data'];
			$this->upload_sales_data 					= $opts['upload_sales_data'];
		} else {
			$this->options['upload_sales_data'] 		= "Yes";
		}
		
		if (isset($opts['upload_replacement_data'])) {
			$this->options['upload_replacement_data']	= $opts['upload_replacement_data'];
			$this->upload_replacement_data 				= $opts['upload_replacement_data'];
		} else {
			$this->options['upload_replacement_data'] 	= "Yes";
		}
		
		if (isset($opts['upload_exclusion_data'])) {
			$this->options['upload_exclusion_data'] 	= $opts['upload_exclusion_data'];
			$this->upload_exclusion_data 				= $opts['upload_exclusion_data'];
		} else {
			$this->options['upload_exclusion_data'] 	= "Yes";
		}
		
		if (isset($opts['upload_product_data'])) {
			$this->options['upload_product_data'] 		= $opts['upload_product_data'];
			$this->upload_product_data 					= $opts['upload_product_data'];
		} else {
			$this->options['upload_product_data'] 		= "Yes";
		}
		
		if (isset($opts['replace_catalog_old_field'])) {
			$this->options['replace_catalog_old_field'] 	= $opts['replace_catalog_old_field'];
			$this->replace_catalog_old_field 				= $opts['replace_catalog_old_field'];
		} else {
			$this->options['replace_catalog_old_field'] 	= null;
		}
		
		if (isset($opts['replace_catalog_new_field'])) {
			$this->options['replace_catalog_new_field'] 	= $opts['replace_catalog_new_field'];
			$this->replace_catalog_new_field 				= $opts['replace_catalog_new_field'];
		} else {
			$this->options['replace_catalog_new_field'] 	= null;
		}

		if (isset($opts['showUploadDetails'])) {
			$this->options['showUploadDetails'] 		= $opts['showUploadDetails'];
			$this->showExportDetails 					= $opts['showUploadDetails'];
		} else {
			$this->options['showUploadDetails'] 		= "No";
		}
	}
	
	
	public function setProductAttributes($attrs)
	{
		$this->product_attributtes = $attrs;
	}
	
	
	public function getProductAttributes()
	{
		return $this->product_attributtes;
	}
	
	
	function getUploadOptionsPath() 
	{
		return Mage::getBaseDir().DS.'app'.DS.'code'.DS.'local'.DS.'FourTell'.DS.'Recommend'.DS.'etc'.DS.'upload.xml';
	}
	
	
	public function load()
	{
		$this->setOptions(get_object_vars(simplexml_load_file($this->getUploadOptionsPath())));

		return $this->getOptions();
	}
	
	
	public function save($post)
	{
		$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><upload_options />");
		
		$ids = $this->getExcludePostIds($post);
		$fields = array();
		foreach($ids as $id) {
			$fields[] = $this->getExcludePostField($post, $id);
		}
		$exclude_options = $xml->addChild('exclude_options');
		foreach($fields as $field) {
			$fieldXml = $exclude_options->addChild('option');
			$fieldXml->addChild('field', 	$field['Name']);
			$fieldXml->addChild('compare', 	$field['Compare']);
			$fieldXml->addChild('value', 	$field['Value']);
		}
		
		
		$ids = $this->getReplacePostIds($post);
		$fields = array();
		foreach($ids as $id) {
			$fields[] = $this->getReplacePostField($post, $id);
		}
		$exclude_options = $xml->addChild('replace_options');
		foreach($fields as $field) {
			$fieldXml = $exclude_options->addChild('option');
			$fieldXml->addChild('oldid', 	$field['oldid']);
			$fieldXml->addChild('newid', 	$field['newid']);
		}
		
		$xml->addChild('upload_sales_data', 		$post['upload_sales_data']);
		$xml->addChild('upload_replacement_data', 	$post['upload_replacement_data']);
		$xml->addChild('upload_product_data', 		$post['upload_product_data']);
		$xml->addChild('upload_exclusion_data', 	$post['upload_exclusion_data']);
		$xml->addChild('replace_catalog_old_field', $post['replace_catalog_old_field']);
		$xml->addChild('replace_catalog_new_field', $post['replace_catalog_new_field']);
		$xml->addChild('showUploadDetails', 		$post['showUploadDetails']);
		
		$xmlPath = $this->getUploadOptionsPath();

		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		
		$sf = fopen($xmlPath, "w+");
		fwrite($sf, $dom->saveXML(), strlen($dom->saveXML()));
		fclose($sf);
	}
	
	
    function getExcludePostIds($post) 
    {
		$ids = array();
		
		foreach($post as $k => $v) {
			if (strpos($k, "excludeField_Name") !== false && trim($v) != "") {
				$parts = explode("_", trim($k));
				$ids[] = $parts[2];
			}
		}
		
		return $ids;
    }
	
	
    function getExcludePostField($post, $id) 
    {
		$data = array();
		
		foreach($post as $k => $v) {
			if (strpos($k, "excludeField_") !== false && trim($v) != "") {
				$parts = explode("_", trim($k));
				if ($id == $parts[2]) {
					$data[$parts[1]] = trim($v);
				}
			}
		}
		
		return $data;
    }
	
	
	
	
    function getReplacePostIds($post) 
    {
		$ids = array();
		
		foreach($post as $k => $v) {
			if (strpos($k, "replaceField_oldid") !== false && trim($v) != "") {
				$parts = explode("_", trim($k));
				$ids[] = $parts[2];
			}
		}
		
		return $ids;
    }
	
	
    function getReplacePostField($post, $id) 
    {
		$data = array();
		
		foreach($post as $k => $v) {
			if (strpos($k, "replaceField_") !== false && trim($v) != "") {
				$parts = explode("_", trim($k));
				if ($id == $parts[2]) {
					$data[$parts[1]] = trim($v);
				}
			}
		}
		
		return $data;
    }
	
	
	function _toArray() 
	{
		$data = array();
		
		foreach($this->getExcludeOptions()->getNode() as $opt) {
			$ex = array(
				'field' 	=> (string)$opt->field,
				'compare' 	=> (string)$opt->compare,
				'value' 	=> (string)$opt->value
			);

			$data['exclude_options'][] = $ex;
		}
		
		return $data;
	}


}
?>