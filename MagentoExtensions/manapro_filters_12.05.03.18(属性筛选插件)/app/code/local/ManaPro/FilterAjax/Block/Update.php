<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

class ManaPro_FilterAjax_Block_Update extends Mage_Core_Block_Abstract {
	public function getSelectors() {
		$result = array();
		foreach ($this->_blockNames as $blockName) $result[] = '.mb-'.str_replace('.', '-', $blockName);
		return $result;
	}
    protected function _toHtml()
    {
		Mage::helper('mana_core/js')->options('#m-filter-ajax', array(
			'exactUrls' => Mage::helper('manapro_filterajax')->getExactUrls(),
			'partialUrls' => Mage::helper('manapro_filterajax')->getPartialUrls(),
			'urlExceptions' => Mage::helper('manapro_filterajax')->getUrlExceptions(),
			'selectors' => $this->getSelectors(),
			'debug' => Mage::getStoreConfigFlag('mana_filters/ajax/debug'),
			'progress' => Mage::getStoreConfigFlag('mana_filters/ajax/progress'),
            'scroll' => Mage::getStoreConfigFlag('mana_filters/ajax/scroll'),
            'method' => $this->getMethod(),
		));
        return '';
    }
    protected $_method;
    public function getMethod() {
        if (!$this->_method) {
            $this->_method = Mage::getStoreConfig('mana_filters/ajax/method');
        }
        return $this->_method;
    }
    public function markUpdatable($blockName, $html) {
    	if (isset($this->_blockNames[$blockName])) {
    	    switch ($this->getMethod()) {
                case ManaPro_FilterAjax_Model_Method::MARK_WITH_CSS_CLASS:
                    try {
                        /* @var $reader Mana_Core_Model_Html_Reader */
                        $reader = Mage::getModel('mana_core/html_reader')->setSource($html);
                        /* @var $parser ManaPro_FilterAjax_Model_Marker */
                        $parser = Mage::getModel('manapro_filterajax/marker', array(
                            'reader' => $reader,
                            'block_name' => str_replace('.', '-', $blockName),
                        ));
                        $parser->parseContent();
                        return $parser->getFilteredOutput();
                    }
                    catch (Exception $e) {
                        Mage::log($e->getMessage() . "\n\n", Zend_Log::WARN, 'content-parser.log');
                        return $html;
                    }
                case ManaPro_FilterAjax_Model_Method::WRAP_INTO_CONTAINER:
                    return '<div class="m-block mb-'. str_replace('.', '-', $blockName).'">'.$html.'</div>';
                default:
                    throw new Exception('Not implemented');
            }
    	}
    	else {
    		return $html;
    	}
    }
    public function toAjaxHtml() {
    	$result = array();
    	$updates = array();
    	$script = '';
    	foreach ($this->_blockNames as $blockName) {
    		if ($block = $this->getLayout()->getBlock($blockName)) {
	    		$html = Mage::getSingleton('core/url')->sessionUrlVar($block->toHtml());
                $updates['.mb-' . str_replace('.', '-', $blockName)] = utf8_encode($html);
    		}
    	}
    	
    	$result['update'] = $updates;
    	$result['script'] = $script;
    	$result['options'] = $this->getLayout()->getBlock('m_js')->getOptions(); // left for future - now all options are static
    	if ($this->getLayout()->getBlock('head')) {
    	    $headBlock = $this->getLayout()->getBlock('head');
    	    $headBlock->getTitle();
    	    $result['title'] = $headBlock->getData('title');
        }
    	if (Mage::getStoreConfigFlag('mana_filters/ajax/debug')) {
    		Mage::log("\n".$script."\n\n", Zend_Log::DEBUG, 'content-script.log');
    	}
    	return $result;
    }
    protected $_blockNames = array();
    public function addBlock($blockName) {
    	$this->_blockNames[$blockName] = $blockName;
		return $this;
    }
}