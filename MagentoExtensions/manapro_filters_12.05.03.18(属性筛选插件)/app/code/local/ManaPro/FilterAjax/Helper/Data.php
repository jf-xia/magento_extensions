<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterAjax
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/* BASED ON SNIPPET: New Module/Helper/Data.php */
/**
 * Generic helper functions for ManaPro_FilterAjax module. This class is a must for any module even if empty.
 * @author Mana Team
 */
class ManaPro_FilterAjax_Helper_Data extends Mage_Core_Helper_Abstract {
	protected $_exactUrls = array();
	protected $_partialUrls = array();
	protected $_urlExceptions = array();
	public function registerExactUrl($url) {
		if ($this->_isEnabled()) {
			$this->_exactUrls[$url] = $url;
		}
		return $this;
	}
	public function registerPartialUrl($url) {
		if ($this->_isEnabled()) {
			$this->_partialUrls[$url] = $url;
		}
		return $this;
	}
	public function registerUrlException($url) {
		if ($this->_isEnabled()) {
			$this->_urlExceptions[$url] = $url;
		}
		return $this;
	}
	public function getExactUrls() {
		return $this->_exactUrls;
	}
	public function getPartialUrls() {
		return $this->_partialUrls;
	}
	public function getUrlExceptions() {
		return $this->_urlExceptions;
	}
	public function renderUrls($actionUrl) {
	}

	protected $_detected = false;
	protected $_enabled = false;
	protected function _isEnabled() {
		if (!$this->_detected) {
			switch (Mage::getStoreConfig('mana_filters/ajax/mode')) {
				case ManaPro_FilterAjax_Model_Mode::OFF:
					break;
				case ManaPro_FilterAjax_Model_Mode::ON_FOR_ALL:
					$this->_enabled = true;
					break;
				case ManaPro_FilterAjax_Model_Mode::ON_FOR_USERS:
					$this->_enabled = true;
					foreach (explode(';', Mage::getStoreConfig('mana_filters/ajax/bots')) as $agent) {
						if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], trim($agent)) !== false) {
							$this->_enabled = false;
							break;
						}
					}
					break;
				default:
					throw new Exception('Not implemented');
			}
			$this->_detected = true;
		}
		return $this->_enabled;
	}
}