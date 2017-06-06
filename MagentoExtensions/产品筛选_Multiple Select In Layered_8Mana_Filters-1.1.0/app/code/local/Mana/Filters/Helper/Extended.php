<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Encapsulates extensibility points for Mana_Filters module. Mana_Filters module internals call this helper to 
 * invoke this module extensions. Extension mechanisms include registration of additional functionality via 
 * config.xml, subscribing to events provided by this module, etc. 
 * @author Mana Team
 */
class Mana_Filters_Helper_Extended extends Mage_Core_Helper_Abstract {
	/**
	 * Provides phtml template file name to be used with this filter. Checks global configuration specific for 
	 * specified filter block
	 * @param Mage_Catalog_Block_Layer_Filter_Abstract $filterBlock Block to be rendered in returned template
	 * @return string
	 */
	public function getFilterTemplate($filterBlock) {
		/* @var $helper Mana_Filters_Helper_Data */ $helper = Mage::helper(strtolower('Mana_Filters'));
		$type = $helper->getBlockType($filterBlock);
		if (/* @var $displayOptions Mage_Core_Model_Config_Element */ $displayOptions = Mage::getConfig()
			->getNode('mana_filters/display/'.$type)) 
		{
			$position = 0;
			/* @var $default Mage_Core_Model_Config_Element */ $default = null;
			foreach ($displayOptions->children() as $name => /* @var $options Mage_Core_Model_Config_Element */ $options) {
				if (!$position || $position > (string)$options->position) {
					$position = (string)$options->position;
					$default = $options;	
				}
			}
			if ($default) return (string)$default->template;
		}
		throw new Mage_Core_Exception($this->__('Filters of type "%s" can not be displayed - no template installed.'));
	}
	
	/**
	 * Modifies filter items and filter model itself as specified by extensions subscribed to 
	 * mana_filters_process_items event.
	 * @param Mage_Catalog_Model_Layer_Filter_Abstract $filter
	 * @param array $items
	 */
	public function processFilterItems($filter, $items) {
		$wrappedItems = new Varien_Object;
		$wrappedItems->setItems($items);
		Mage::dispatchEvent('mana_filters_process_items', array('filter' => $filter, 'items' => $wrappedItems));
		return $wrappedItems->getItems();
	}

	/**
	 * Returns rendered additional markup registered by extensions in configuration under $name key 
	 * @param string $name
	 * @param array $parameters
	 * @return string
	 */
	public function getNamedHtml($name, $parameters = array()) {
		if (/* @var $markups Mage_Core_Model_Config_Element */ $markups = Mage::getConfig()
			->getNode('mana_filters/markup/'.$name)) 
		{
			$templates = array();
			foreach ($markups->children() as $name => /* @var $markup Mage_Core_Model_Config_Element */ $markup) {
				$templates[] = array('name' => $name, 'position' => (string)$markup->position, 'template' => (string)$markup->template);
			}
			usort($templates, array('Mana_Filters_Helper_Extended', '_sortByPosition'));
			$result = '';
			foreach ($templates as $template) {
				$filename = Mage::getBaseDir('design').DS.
					Mage::getDesign()->getTemplateFilename($template['template'], array('_relative'=>true));
				if (file_exists($filename)) {
        			$result .= $this->_fetchHtml($filename, $parameters);
				}
			}
			return $result;
		}
		else return '';
	}
	protected function _fetchHtml($filename, $parameters) {
        extract ($parameters, EXTR_OVERWRITE);
        ob_start();
        try {
            include $filename;
        } 
        catch (Exception $e) {
            ob_get_clean();
            throw $e;
        }
        return ob_get_clean();
	}
	public static function _sortByPosition($a, $b) {
		if ($a['position'] < $b['position']) return -1;
		elseif ($a['position'] > $b['position']) return 1;
		else return 0;
	}
	public function getFilterUrl($route = '', $params = array()) {
		$wrappedParams = new Varien_Object;
		$wrappedParams->setParams($params);
		$wrappedParams->setIsHandled(false);
		$wrappedParams->setResult('');
		Mage::dispatchEvent('mana_filters_url', array('route' => $route, 'params' => $wrappedParams));
		if ($wrappedParams->getIsHandled()) {
			return $wrappedParams->getResult();
		}
		else {
			return Mage::getUrl($route, $params);
		}
	}
	public function getPriceRange($index, $range) {
		$wrappedParams = new Varien_Object;
		$wrappedParams->setIsHandled(false);
		$wrappedParams->setResult(array());
		Mage::dispatchEvent('mana_filters_price_range', array('index' => $index, 'range' => $range, 'params' => $wrappedParams));
		if ($wrappedParams->getIsHandled()) {
			return $wrappedParams->getResult();
		}
		else {
			return array('from' => $range * ($index - 1), 'to' => $range * $index);
		}
	}
}