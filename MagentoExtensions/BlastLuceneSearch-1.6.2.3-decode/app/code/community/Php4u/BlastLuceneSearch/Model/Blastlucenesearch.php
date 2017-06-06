<?php
/**
 * @category   Php4u
 * @package    Php4u_BlastLuceneSearch
 * @author     Marcin Szterling <marcin@php4u.co.uk>
 * @copyright  Php4u Marcin Szterling (c) 2011
 * @license http://php4u.co.uk/licence/
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * Any form of ditribution, sell, transfer forbidden, reverse engineering forbidden - see licence above
 *
 * Code was obfusacted due to previous licence violations
 */
			 class Php4u_BlastLuceneSearch_Model_BlastLuceneSearch extends Mage_Core_Model_Abstract {
	protected $_index;
	protected $_storeId;
	protected $_valid = true;
	static $_logname = 'blastlucenesearch.log';
	static protected $_lic;
	static $_num_processed = array() ;
	protected $_filesize=0;
	public function __construct($_5e8a223a326d56b27e0cf4413f58671f = array()) {
		if (isset($_5e8a223a326d56b27e0cf4413f58671f['store'])) {
			if ($_5e8a223a326d56b27e0cf4413f58671f['store'] instanceof Mage_Core_Model_Store) $this->setStoreId($_5e8a223a326d56b27e0cf4413f58671f['store']->getId());
		}
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive());
		Zend_Search_Lucene_Search_QueryParser::setDefaultOperator(Zend_Search_Lucene_Search_QueryParser::B_OR);
		Zend_Search_Lucene_Storage_Directory_Filesystem::setDefaultFilePermissions(0777);
		$this->_prepareApp();
	}
	public function _construct() {
		parent::_construct();
		$this->_init('blastlucenesearch/blastlucenesearch');
	}
	public function setStoreId($_0f8db65491b54a139e3040b6c2cccef7) {
		$this->_storeId = intval($_0f8db65491b54a139e3040b6c2cccef7);
		$this->_index = null;
		return $this;
	}
	public function getStoreId() {
		if (empty($this->_storeId)) {
			$this->setStoreId(Mage::app()->getStore()->getId());
		}
		return $this->_storeId;
	}
	public function getIndexFolder($_3244265c4828307734a3f62db0233e1d=true) {
		if ($this->getStoreId() < 1) {
			$this->setStoreId(Mage::app()->getStore()->getStoreId());
		}
		$_57583ceaaf7f2d0b967c7b29cd4e30a7 = Mage::getBaseDir('var').DS.'indexer'.DS.$this->getStoreId().DS;
		if (!is_dir($_57583ceaaf7f2d0b967c7b29cd4e30a7)) {
			mkdir($_57583ceaaf7f2d0b967c7b29cd4e30a7, 0777, true);
			if (stripos(PHP_OS, 'win') === FALSE) {
				$_ab4a866184a9dcf08d2460d56ccf6721 = array( 'name' => 'apache', 'gecos' => 'apache' );
				if (function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
					$_ab4a866184a9dcf08d2460d56ccf6721 = posix_getpwuid(posix_geteuid());
					@chown($_57583ceaaf7f2d0b967c7b29cd4e30a7, isset($_ab4a866184a9dcf08d2460d56ccf6721['name']) ? $_ab4a866184a9dcf08d2460d56ccf6721['name'] : get_current_user());
					@chgrp($_57583ceaaf7f2d0b967c7b29cd4e30a7, isset($_ab4a866184a9dcf08d2460d56ccf6721['gecos']) ? $_ab4a866184a9dcf08d2460d56ccf6721['gecos'] : get_current_user());
				}
			}
		}
		return $_57583ceaaf7f2d0b967c7b29cd4e30a7;
	}
	public function getIndex($_3244265c4828307734a3f62db0233e1d=true) {
		if (!$this->_index) {
			try {
				$this->_index = Zend_Search_Lucene::open($this->getIndexFolder($_3244265c4828307734a3f62db0233e1d));
			}
			catch (Zend_Search_Lucene_Exception $_a98459d89a337de5a3cbc5db561b45f8) {
				$this->_index = Zend_Search_Lucene::create($this->getIndexFolder($_3244265c4828307734a3f62db0233e1d));
			}
			if (!$this->_index) {
				throw new Exception("Problem creating Lucene index in ".$this->getIndexFolder());
			}
		}
		return $this->_index;
	}
	public function removeProductFromIndex($_684842bcb895b7bec57b6015cc62a5fb, $_4f85e76960e2c40d08546acf1c5f9b94 = false) {
		try {
			if (is_array($_684842bcb895b7bec57b6015cc62a5fb)) {
				foreach($_684842bcb895b7bec57b6015cc62a5fb as $_506d7ca1425a89cd2fb52f63efca0cf5) {
					$this->removeProductFromIndex(intval($_506d7ca1425a89cd2fb52f63efca0cf5), $_4f85e76960e2c40d08546acf1c5f9b94);
				}
			}
			elseif ($_684842bcb895b7bec57b6015cc62a5fb > 0) {
				$_597281a31ad73195fa5c3350a184ceb5 = new Zend_Search_Lucene_Index_Term($_684842bcb895b7bec57b6015cc62a5fb, 'entity_id');
				$_2c3ba70cb520bed3c844b88fa188bc29 = $this->getIndex()->termDocs($_597281a31ad73195fa5c3350a184ceb5);
				foreach ($_2c3ba70cb520bed3c844b88fa188bc29 as $_ece3632cd7627d56148020253dae1f26) {
					$this->getIndex()->delete($_ece3632cd7627d56148020253dae1f26);
				}
				$this->getIndex()->commit();
				if (!$_4f85e76960e2c40d08546acf1c5f9b94) {
					$this->markAsIndexNotRequired($_684842bcb895b7bec57b6015cc62a5fb);
				}
				else {
					$this->markAsIndexRequired($_684842bcb895b7bec57b6015cc62a5fb);
				}
			}
		}
		catch (Exception $_fbece582ed7c0bbfd80e779288ab13e9) {
			die($_fbece582ed7c0bbfd80e779288ab13e9->getMessage());
		}
		return $this;
	}
	protected function _getNumProcessed() {
		if (!isset(self::$_num_processed[$this->getStoreId()])) {
			self::$_num_processed[$this->getStoreId()] = 0;
		}
		return self::$_num_processed[$this->getStoreId()];
	}
	protected function _setNumProcessed($_dc836983a811148e6807a523d12aeb46 = 0) {
		self::$_num_processed[$this->getStoreId()] = $_dc836983a811148e6807a523d12aeb46;
	}
	protected function _increaseNumProcessed() {
		self::$_num_processed[$this->getStoreId()] = $this->_getNumProcessed() + 1;
	}
	public function logSize() {
		$this->_index = null;
		self::log("[STATS] Store [".$this->getStoreId()."] Documents in index: ".$this->getSize());
	}
	public function removeAll() {
		self::log("[REMOVEALL] Start");
		for ($_c85e48c002c8d0ceda2f972327e78ee5=0;$_c85e48c002c8d0ceda2f972327e78ee5<$this->getIndex()->maxDoc();
		$_c85e48c002c8d0ceda2f972327e78ee5++) {
			if (!$this->getIndex()->isDeleted($_c85e48c002c8d0ceda2f972327e78ee5)) {
				$this->getIndex()->delete($_c85e48c002c8d0ceda2f972327e78ee5);
			}
		}
		$this->getIndex()->commit();
		$this->optimizeIndex();
		self::log("[REMOVEALL] Done");
	}
	public function removeNotExistingFromIndex() {
		self::log("[removeNotExistingFromIndex] start");
		$_7d402d41acd02f32e5bb4e4b403efc75 = array();
		self::log("[removeNotExistingFromIndex] documents to analize: ".$this->getIndex()->maxDoc());
		for ($_c85e48c002c8d0ceda2f972327e78ee5=0;$_c85e48c002c8d0ceda2f972327e78ee5<$this->getIndex()->maxDoc();
		$_c85e48c002c8d0ceda2f972327e78ee5++) {
			if (!$this->getIndex()->isDeleted($_c85e48c002c8d0ceda2f972327e78ee5)) {
				$_0260788546415e19a3e002563cfb4810 = $this->getIndex()->getDocument($_c85e48c002c8d0ceda2f972327e78ee5);
				$_58096a6bc9058e927b9bde03d5f66f12 = "SELECT entity_id from `".self::getProductTableName()."` WHERE entity_id = '{$_0260788546415e19a3e002563cfb4810->entity_id}'";
				if (!$this->_getAdapter()->fetchOne($_58096a6bc9058e927b9bde03d5f66f12)) {
					$_7d402d41acd02f32e5bb4e4b403efc75[] = $_c85e48c002c8d0ceda2f972327e78ee5;
				}
			}
		}
		self::log("[removeNotExistingFromIndex] to delete count: ".count($_7d402d41acd02f32e5bb4e4b403efc75));
		foreach ($_7d402d41acd02f32e5bb4e4b403efc75 as $_0deef480d4430019a67012f55598c2d0) {
			$this->getIndex()->delete($_0deef480d4430019a67012f55598c2d0);
		}
		$this->getIndex()->commit();
		$this->optimizeIndex();
		self::log("[removeNotExistingFromIndex] done");
	}
	protected function _getFieldBoost($_8b120a39ecd123195c08fdb025b8424d) {
		if (!in_array($_8b120a39ecd123195c08fdb025b8424d, array('product_name', 'product_sku', 'product_description', 'product_short_description'))) {
			return 1;
		}
		$_c9a764f3122ed42cc7b2378cd3b0fc73 = Mage::getStoreConfig('php4u/index_boost/'.$_8b120a39ecd123195c08fdb025b8424d, $this->getStoreId());
		$_c9a764f3122ed42cc7b2378cd3b0fc73 = @floatval(str_replace('_','.', $_c9a764f3122ed42cc7b2378cd3b0fc73));
		if ($_c9a764f3122ed42cc7b2378cd3b0fc73<1 or $_c9a764f3122ed42cc7b2378cd3b0fc73>10) {
			$_c9a764f3122ed42cc7b2378cd3b0fc73 = 1;
		}
		return $_c9a764f3122ed42cc7b2378cd3b0fc73;
	}
	protected function _getFieldBoostNew($_8b120a39ecd123195c08fdb025b8424d) {
		$_c9a764f3122ed42cc7b2378cd3b0fc73 = 1;
		$_22f171ad59a71c4eb8bbb9d7e0d92459 = Mage::getStoreConfig('php4u/index_boost/list', $this->getStoreId());
		if (!empty($_22f171ad59a71c4eb8bbb9d7e0d92459)) {
			if (!$_22f171ad59a71c4eb8bbb9d7e0d92459 = @unserialize($_22f171ad59a71c4eb8bbb9d7e0d92459)) {
				return $_c9a764f3122ed42cc7b2378cd3b0fc73;
			}
			foreach ($_22f171ad59a71c4eb8bbb9d7e0d92459 as $_7712744f253d0b961df218aaed2fde51) {
				if (isset($_7712744f253d0b961df218aaed2fde51['product_attribute']) && isset($_7712744f253d0b961df218aaed2fde51['search_boost'])) {
					if ($_7712744f253d0b961df218aaed2fde51['product_attribute'] == $_8b120a39ecd123195c08fdb025b8424d) {
						$_c9a764f3122ed42cc7b2378cd3b0fc73 = floatval($_7712744f253d0b961df218aaed2fde51['search_boost']);
					}
				}
			}
		}
		if ($_c9a764f3122ed42cc7b2378cd3b0fc73<1 or $_c9a764f3122ed42cc7b2378cd3b0fc73>100) {
			$_c9a764f3122ed42cc7b2378cd3b0fc73 = 1;
		}
		return $_c9a764f3122ed42cc7b2378cd3b0fc73;
	}
	protected function _createVariations($_2b86beb1e6bcf66c76c107f428c5c728) {
		if (!Mage::getStoreConfigFlag('php4u/php4u_group/use_stemmer', $this->getStoreId())) {
			return $_2b86beb1e6bcf66c76c107f428c5c728;
		}
		$_568908e8acf34fa0ca5213b24731f909 = explode(' ',$_2b86beb1e6bcf66c76c107f428c5c728);
		$_5d5f96be35274772bccc005c80055a58 = array();
		$_9211df7a74eb5297e64363fcc801d25f = array();
		foreach ($_568908e8acf34fa0ca5213b24731f909 as $_2449f1250dd274d90823ff25bcee0c18) {
			if (strlen($_2449f1250dd274d90823ff25bcee0c18) > 2 && !is_numeric($_2449f1250dd274d90823ff25bcee0c18)) {
				$_5d5f96be35274772bccc005c80055a58[] = Php4u_BlastLuceneSearch_Model_Inflect::pluralize($_2449f1250dd274d90823ff25bcee0c18);
				$_9211df7a74eb5297e64363fcc801d25f[] = Php4u_BlastLuceneSearch_Model_Inflect::singularize($_2449f1250dd274d90823ff25bcee0c18);
			}
		}
		$_1c8c60a34605501165604df86fb932b2 = array_merge($_5d5f96be35274772bccc005c80055a58, $_9211df7a74eb5297e64363fcc801d25f);
		$_1c8c60a34605501165604df86fb932b2 = array_merge($_1c8c60a34605501165604df86fb932b2, $_568908e8acf34fa0ca5213b24731f909);
		$_1c8c60a34605501165604df86fb932b2 = array_unique($_1c8c60a34605501165604df86fb932b2);
		$_0e69487daa56edbad182d0e079b813e7 = implode(' ', $_1c8c60a34605501165604df86fb932b2);
		return $_0e69487daa56edbad182d0e079b813e7;
	}
	protected function _translatePosition($_13acf4a83b0686a1639a30609f8e7599) {
		$_13acf4a83b0686a1639a30609f8e7599 = intval($_13acf4a83b0686a1639a30609f8e7599);
		switch ($_13acf4a83b0686a1639a30609f8e7599) {
			case 0: return 0;
			case 10: return 0.01;
			case 100: return 1.01;
		}
		return $_13acf4a83b0686a1639a30609f8e7599;
	}
	private function _prepareStringToAddToIndex($_2b86beb1e6bcf66c76c107f428c5c728) {
		$_2b86beb1e6bcf66c76c107f428c5c728 = $this->_tokenizeHypens($_2b86beb1e6bcf66c76c107f428c5c728);
		$_2b86beb1e6bcf66c76c107f428c5c728 = $this->_tokenizeNumbers($_2b86beb1e6bcf66c76c107f428c5c728);
		$_2b86beb1e6bcf66c76c107f428c5c728 = $this->cleanString($_2b86beb1e6bcf66c76c107f428c5c728);
		$_2b86beb1e6bcf66c76c107f428c5c728 = $this->_createVariations($_2b86beb1e6bcf66c76c107f428c5c728);
		return $_2b86beb1e6bcf66c76c107f428c5c728;
	}
	public function addProductToIndex($_684842bcb895b7bec57b6015cc62a5fb, $_66ecc94926fccf958c92539841d8a518, $_c7e63446d2038675c2d4c2e15b9b0793 = false, $_0931fc9ddef0d26a1e96967a8ff9922f = true) {
		if (!$this->isLicValid()) {
			return false;
		}
		if (!$this->_index) {
			$this->getIndex(false);
		}
		if ($_c7e63446d2038675c2d4c2e15b9b0793) {
			$this->removeProductFromIndex($_684842bcb895b7bec57b6015cc62a5fb);
		}
		if (!is_array($_66ecc94926fccf958c92539841d8a518)) {
			$_0016f68eca87118d362763abf8a46563 = array();
			$_0016f68eca87118d362763abf8a46563['data'] = $_66ecc94926fccf958c92539841d8a518;
			$_0016f68eca87118d362763abf8a46563['product_name'] = '';
			$_0016f68eca87118d362763abf8a46563['product_description'] = '';
			$_0016f68eca87118d362763abf8a46563['product_short_description'] = '';
			$_0016f68eca87118d362763abf8a46563['product_lucene_product_position'] = 0;
			$_66ecc94926fccf958c92539841d8a518 = $_0016f68eca87118d362763abf8a46563;
		}
		else {
			if (!isset($_66ecc94926fccf958c92539841d8a518['product_lucene_product_position'])) {
				$_66ecc94926fccf958c92539841d8a518['product_lucene_product_position'] = 0;
			}
		}
		if (empty($_66ecc94926fccf958c92539841d8a518['data']) || empty($_66ecc94926fccf958c92539841d8a518['data'])) {
			self::log("[INDEXER] Indexing data is empty", Zend_Log::ERR);
			return false;
		}
		$_717943ad14024a207c7d34afed5865a7 = new Zend_Search_Lucene_Document();
		$_50c01fe4d754dce37a9654af14b712db = $this->_prepareStringToAddToIndex($_66ecc94926fccf958c92539841d8a518['data']);
		$_717943ad14024a207c7d34afed5865a7->addField(Zend_Search_Lucene_Field::keyword('entity_id', $_684842bcb895b7bec57b6015cc62a5fb));
		$_717943ad14024a207c7d34afed5865a7->addField(Zend_Search_Lucene_Field::text('index_data', $_50c01fe4d754dce37a9654af14b712db, 'utf-8'));
		$_717943ad14024a207c7d34afed5865a7->addField(Zend_Search_Lucene_Field::keyword('index_data_keyword', $_50c01fe4d754dce37a9654af14b712db, 'utf-8'));
		unset($_66ecc94926fccf958c92539841d8a518['data']);
		foreach ($_66ecc94926fccf958c92539841d8a518 as $_4c82eed2b785244d46bb9b9bdba42ed0 => $_7567b6433798b6dea906624e3459655c) {
			$_207a5825ae187ee7a877717b759dc983 = Zend_Search_Lucene_Field::text($_4c82eed2b785244d46bb9b9bdba42ed0, $this->_prepareStringToAddToIndex($_7567b6433798b6dea906624e3459655c), 'utf-8');
			$_207a5825ae187ee7a877717b759dc983->boost = $this->_getFieldBoost($_4c82eed2b785244d46bb9b9bdba42ed0);
			$_717943ad14024a207c7d34afed5865a7->addField($_207a5825ae187ee7a877717b759dc983, 'utf-8');
		}
		$this->getIndex()->addDocument($_717943ad14024a207c7d34afed5865a7);
		if (TRUE === $_0931fc9ddef0d26a1e96967a8ff9922f) $this->getIndex()->commit();
		if (!$_c7e63446d2038675c2d4c2e15b9b0793) {
			$this->markAsIndexNotRequired($_684842bcb895b7bec57b6015cc62a5fb);
		}
		if (Mage::getStoreConfigFlag('php4u/php4u_group/products_log_enabled', $this->getStoreId()) === TRUE) {
			self::log("[INDEXER] Store [".$this->getStoreId()."] Added product ID [$_684842bcb895b7bec57b6015cc62a5fb] to index");
			self::logSize();
		}
		$this->_increaseNumProcessed();
		$_ab99e2a49ecfe2a2375b28010ef79614 = Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_document_limiter', $this->getStoreId());
		if ($_ab99e2a49ecfe2a2375b28010ef79614 > 0) {
			if ($this->_getNumProcessed() >= $_ab99e2a49ecfe2a2375b28010ef79614) {
				$this->_setNumProcessed(0);
				self::log("[INDEXER] Running optimizer as we reached $_ab99e2a49ecfe2a2375b28010ef79614 documents.");
				$this->optimizeIndex();
			}
		}
		return true;
	}
	private function _getAttributeId() {
		$_ece3632cd7627d56148020253dae1f26 = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','lucene_indexed');
		if (!$_ece3632cd7627d56148020253dae1f26) {
			self::log("Problem with finding product attribute! Problem with installation?", Zend_Log::CRIT);
			return false;
		}
		return $_ece3632cd7627d56148020253dae1f26;
	}
	public function markAsIndexRequired($_506d7ca1425a89cd2fb52f63efca0cf5) {
		if ($_506d7ca1425a89cd2fb52f63efca0cf5 > 0 && $this->getStoreId() > 0 && $this->_getAttributeId() !== false) {
			$_5e4dc48878a719586fd1017a3094b0c5 = array('null', $this->_getProductEntityTypeId(), Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','lucene_indexed'), $this->getStoreId(), $_506d7ca1425a89cd2fb52f63efca0cf5, 0);
			$_5e4dc48878a719586fd1017a3094b0c5 = implode(',', $_5e4dc48878a719586fd1017a3094b0c5);
			$_58096a6bc9058e927b9bde03d5f66f12 = "REPLACE INTO `".self::getProductTableIntName()."` VALUES ({$_5e4dc48878a719586fd1017a3094b0c5})";
			try {
				$this->_getAdapter()->query($_58096a6bc9058e927b9bde03d5f66f12);
			}
			catch (Exception $_fbece582ed7c0bbfd80e779288ab13e9) {
				if (stripos($_fbece582ed7c0bbfd80e779288ab13e9->getMessage(),'Integrity constraint violation') === FALSE) {
					self::log($_fbece582ed7c0bbfd80e779288ab13e9->getMessage(), Zend_Log::ERR);
				}
				else {
					self::log("[REQ]Product $_506d7ca1425a89cd2fb52f63efca0cf5 cannot be found");
				}
			}
		}
	}
	public function markAsIndexNotRequired($_506d7ca1425a89cd2fb52f63efca0cf5) {
		if ($_506d7ca1425a89cd2fb52f63efca0cf5 > 0 && $this->getStoreId() > 0 && $this->_getAttributeId() !== false) {
			$_5e4dc48878a719586fd1017a3094b0c5 = array('null', $this->_getProductEntityTypeId(), Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','lucene_indexed'), $this->getStoreId(), $_506d7ca1425a89cd2fb52f63efca0cf5, 1);
			$_5e4dc48878a719586fd1017a3094b0c5 = implode(',', $_5e4dc48878a719586fd1017a3094b0c5);
			$_58096a6bc9058e927b9bde03d5f66f12 = "REPLACE INTO `".self::getProductTableIntName()."` VALUES ({$_5e4dc48878a719586fd1017a3094b0c5})";
			try {
				$this->_getAdapter()->query($_58096a6bc9058e927b9bde03d5f66f12);
			}
			catch (Exception $_fbece582ed7c0bbfd80e779288ab13e9) {
				if (stripos($_fbece582ed7c0bbfd80e779288ab13e9->getMessage(),'Integrity constraint violation') === FALSE) {
					self::log($_fbece582ed7c0bbfd80e779288ab13e9->getMessage(), Zend_Log::ERR);
				}
				else {
					self::log("[NOT_REQ]Product $_506d7ca1425a89cd2fb52f63efca0cf5 deleted so no updated required");
				}
			}
		}
	}
	public function markAsIndexRequiredForAllStores($_506d7ca1425a89cd2fb52f63efca0cf5) {
		if ($_506d7ca1425a89cd2fb52f63efca0cf5 > 0) {
			foreach (Mage::app()->getStores(false) as $_6f80c34971a9762ddb1d1579fe5576c9) {
				$this->setStoreId($_6f80c34971a9762ddb1d1579fe5576c9->getId());
				$this->markAsIndexRequired($_506d7ca1425a89cd2fb52f63efca0cf5);
			}
		}
	}
	public function markAsIndexNotRequiredForAllStores($_506d7ca1425a89cd2fb52f63efca0cf5) {
		if ($_506d7ca1425a89cd2fb52f63efca0cf5 > 0) {
			foreach (Mage::app()->getStores(false) as $_6f80c34971a9762ddb1d1579fe5576c9) {
				$this->setStoreId($_6f80c34971a9762ddb1d1579fe5576c9->getId());
				$this->markAsIndexNotRequiredRequired($_506d7ca1425a89cd2fb52f63efca0cf5);
			}
		}
	}
	private function _getProductEntityTypeId() {
		if ($this->getData('entity_type_id') < 1) {
			$_97b8c1a7da6e42dd1bf178b302ef2f57 = $this->_getAdapter()->fetchOne("SELECT entity_type_id FROM `".Mage::getSingleton('core/resource')->getTableName('eav_attribute')."` WHERE `attribute_code` LIKE 'lucene_indexed';");
			$this->setData('entity_type_id', $_97b8c1a7da6e42dd1bf178b302ef2f57);
		}
		return $this->getData('entity_type_id');
	}
	public function countIndexed() {
		$_97b8c1a7da6e42dd1bf178b302ef2f57 = $this->_getProductEntityTypeId();
		if ($_97b8c1a7da6e42dd1bf178b302ef2f57) {
			$_58096a6bc9058e927b9bde03d5f66f12 = "SELECT COUNT(*) FROM `".self::getProductTableIntName()."` WHERE entity_type_id = '{$_97b8c1a7da6e42dd1bf178b302ef2f57}' AND attribute_id = '".Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','lucene_indexed')."' AND store_id = '".$this->getStoreId()."' AND (value = 1)";
		}
		return $this->_getAdapter()->fetchOne($_58096a6bc9058e927b9bde03d5f66f12);
	}
	private function _getAdapter() {
		return Mage::getSingleton('core/resource')->getConnection('blastlucenesearch_write');
	}
	public function getEscapeCharacters() {
		return explode(" ",'+ - && & || ! ( ) { } [ ] ^ " ~ * ? : / . , ;');
	}
	public function escapeString($_2b86beb1e6bcf66c76c107f428c5c728) {
		foreach ($this->getEscapeCharacters() as $_64ac996a2fb3261814054490afa4c670) {
			$_2b86beb1e6bcf66c76c107f428c5c728 = str_replace($_64ac996a2fb3261814054490afa4c670, "/".$_64ac996a2fb3261814054490afa4c670, $_2b86beb1e6bcf66c76c107f428c5c728);
		}
		$_2b86beb1e6bcf66c76c107f428c5c728 = trim($_2b86beb1e6bcf66c76c107f428c5c728);
		return $_2b86beb1e6bcf66c76c107f428c5c728;
	}
	public function cleanString($_2b86beb1e6bcf66c76c107f428c5c728) {
		$_faedb47fbde749ffbf519a3b5fd38c03 = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
		$_e2cfc64703f40b5a93c0de15ab5fe9bc = array( '&' => 'and');
		$_2b86beb1e6bcf66c76c107f428c5c728 = mb_strtolower( trim( $_2b86beb1e6bcf66c76c107f428c5c728 ), 'UTF-8' );
		$_2b86beb1e6bcf66c76c107f428c5c728 = str_replace( array_keys($_e2cfc64703f40b5a93c0de15ab5fe9bc), array_values( $_e2cfc64703f40b5a93c0de15ab5fe9bc), $_2b86beb1e6bcf66c76c107f428c5c728 );
		$_2b86beb1e6bcf66c76c107f428c5c728 = preg_replace( $_faedb47fbde749ffbf519a3b5fd38c03, '$1', htmlentities( $_2b86beb1e6bcf66c76c107f428c5c728, ENT_QUOTES, 'UTF-8' ) );
		$_27c5300d0b6fa7b05d89a809d1881505 = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,ą,ę,ó,ł,ć,ś,ń,ź");
		$_962f5ccc3d1db51a5a318c3a1bd468df = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,a,e,o,l,c,s,n,z");
		$_2b86beb1e6bcf66c76c107f428c5c728 = str_replace($_27c5300d0b6fa7b05d89a809d1881505, $_962f5ccc3d1db51a5a318c3a1bd468df, $_2b86beb1e6bcf66c76c107f428c5c728);
		$_2b86beb1e6bcf66c76c107f428c5c728 = str_replace('|', " ", $_2b86beb1e6bcf66c76c107f428c5c728);
		$_cf0e3221ff72a61c5a01244bd5f74ab2 = Mage::getStoreConfig('php4u/php4u_group/php4u_chars2space', $this->getStoreId());
		if (!empty($_cf0e3221ff72a61c5a01244bd5f74ab2)) {
			$_cf0e3221ff72a61c5a01244bd5f74ab2 = str_replace(" ", "", trim($_cf0e3221ff72a61c5a01244bd5f74ab2));
			for ($_2ded5c622c934a71410e4681cfff8291=0;$_2ded5c622c934a71410e4681cfff8291<strlen($_cf0e3221ff72a61c5a01244bd5f74ab2);
			$_2ded5c622c934a71410e4681cfff8291++) {
				$_2b86beb1e6bcf66c76c107f428c5c728 = str_replace($_cf0e3221ff72a61c5a01244bd5f74ab2[$_2ded5c622c934a71410e4681cfff8291], " ", $_2b86beb1e6bcf66c76c107f428c5c728);
			}
		}
		if (!Mage::getStoreConfigFlag('php4u/php4u_group/php4u_lucene_utf8nonstandard', $this->getStoreId())) {
			$_2b86beb1e6bcf66c76c107f428c5c728 = @preg_replace("/[^a-zA-Z0-9\-\/\s]/", '', $_2b86beb1e6bcf66c76c107f428c5c728);
		}
		$_2b86beb1e6bcf66c76c107f428c5c728 = preg_replace( '/\s+/', ' ', $_2b86beb1e6bcf66c76c107f428c5c728 );
		$_2b86beb1e6bcf66c76c107f428c5c728 = trim($_2b86beb1e6bcf66c76c107f428c5c728);
		return $_2b86beb1e6bcf66c76c107f428c5c728;
	}
	public function prepareStringForQuery($_2b86beb1e6bcf66c76c107f428c5c728) {
		return $this->cleanString($_2b86beb1e6bcf66c76c107f428c5c728);
	}
	public function prepareStringForPhrasing($_9c213bb03b2724af53ddb0efb952af4e, $_13adee5425c11f59cd7ba8eeda930bd4 = false) {
		if ($_13adee5425c11f59cd7ba8eeda930bd4 == TRUE) {
			$_1c8c60a34605501165604df86fb932b2 = Mage::helper('core/string')->splitWords($this->prepareStringForQuery($_9c213bb03b2724af53ddb0efb952af4e), true, Mage::getStoreConfig(Mage_CatalogSearch_Model_Query::XML_PATH_MAX_QUERY_WORDS, $this->getStoreId()));
			return $_1c8c60a34605501165604df86fb932b2;
		}
		return explode(' ', $this->prepareStringForQuery($_9c213bb03b2724af53ddb0efb952af4e));
	}
	public function prepareStringForWildcard($_9c213bb03b2724af53ddb0efb952af4e) {
		$_6b26723195078611b14f9989cd976f0e = Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_no_results_searchmode_split', $this->getStoreId());
		self::log("[NO RESULTS SEARCH] Wildcard search mode [$_6b26723195078611b14f9989cd976f0e]");
		if ($_6b26723195078611b14f9989cd976f0e == Php4u_BlastLuceneSearch_Model_Source::LUCENE_PHRASE) {
			$_5c9a1ba56d240539e12b4b166e36ee48 = new Zend_Search_Lucene_Index_Term($this->prepareStringForQuery($_9c213bb03b2724af53ddb0efb952af4e) . '*');
			$_531dcecef8d08920926c6289d50b6cb1 = new Zend_Search_Lucene_Search_Query_Wildcard($_5c9a1ba56d240539e12b4b166e36ee48);
		}
		else {
			$_531dcecef8d08920926c6289d50b6cb1 = new Zend_Search_Lucene_Search_Query_Boolean();
			foreach ($this->prepareStringForPhrasing($_9c213bb03b2724af53ddb0efb952af4e, TRUE) as $_597281a31ad73195fa5c3350a184ceb5) {
				if (strlen($_597281a31ad73195fa5c3350a184ceb5) > 2) {
					$_749742b5f62e4bb43028408449a3d308 = $this->_getFulltextSign(true);
					if ($this->_findSynonim($_597281a31ad73195fa5c3350a184ceb5) !== false) {
						$_749742b5f62e4bb43028408449a3d308 = null;
						$_5c9a1ba56d240539e12b4b166e36ee48 = new Zend_Search_Lucene_Index_Term($this->_findSynonim($_597281a31ad73195fa5c3350a184ceb5) . '*');
						$_d3a49972ac8b6ed37f5a52ec5ae392d3 = new Zend_Search_Lucene_Search_Query_Wildcard($_5c9a1ba56d240539e12b4b166e36ee48);
						$_531dcecef8d08920926c6289d50b6cb1->addSubquery($_d3a49972ac8b6ed37f5a52ec5ae392d3, $_749742b5f62e4bb43028408449a3d308);
					}
					$_5c9a1ba56d240539e12b4b166e36ee48 = new Zend_Search_Lucene_Index_Term($_597281a31ad73195fa5c3350a184ceb5 . '*');
					$_d3a49972ac8b6ed37f5a52ec5ae392d3 = new Zend_Search_Lucene_Search_Query_Wildcard($_5c9a1ba56d240539e12b4b166e36ee48);
					$_531dcecef8d08920926c6289d50b6cb1->addSubquery($_d3a49972ac8b6ed37f5a52ec5ae392d3, $_749742b5f62e4bb43028408449a3d308);
				}
			}
		}
		return $_531dcecef8d08920926c6289d50b6cb1;
	}
	public function prepareStringForFuzzy($_9c213bb03b2724af53ddb0efb952af4e) {
		$_6b26723195078611b14f9989cd976f0e = Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_no_results_searchmode_split', $this->getStoreId());
		self::log("[NO RESULTS SEARCH] Fuzzy search mode [$_6b26723195078611b14f9989cd976f0e]");
		if ($_6b26723195078611b14f9989cd976f0e == Php4u_BlastLuceneSearch_Model_Source::LUCENE_PHRASE) {
			$_5c9a1ba56d240539e12b4b166e36ee48 = new Zend_Search_Lucene_Index_Term($this->prepareStringForQuery($_9c213bb03b2724af53ddb0efb952af4e) );
			$_531dcecef8d08920926c6289d50b6cb1 = new Zend_Search_Lucene_Search_Query_Fuzzy($_5c9a1ba56d240539e12b4b166e36ee48);
		}
		else {
			$_531dcecef8d08920926c6289d50b6cb1 = new Zend_Search_Lucene_Search_Query_Boolean();
			foreach ($this->prepareStringForPhrasing($_9c213bb03b2724af53ddb0efb952af4e) as $_597281a31ad73195fa5c3350a184ceb5) {
				$_749742b5f62e4bb43028408449a3d308 = $this->_getFulltextSign(true);
				if ($this->_findSynonim($_597281a31ad73195fa5c3350a184ceb5) !== false) {
					$_749742b5f62e4bb43028408449a3d308 = null;
					$_5c9a1ba56d240539e12b4b166e36ee48 = new Zend_Search_Lucene_Index_Term($this->prepareStringForQuery($this->_findSynonim($_597281a31ad73195fa5c3350a184ceb5)) );
					$_d3a49972ac8b6ed37f5a52ec5ae392d3 = new Zend_Search_Lucene_Search_Query_Fuzzy($_5c9a1ba56d240539e12b4b166e36ee48);
					$_531dcecef8d08920926c6289d50b6cb1->addSubquery($_d3a49972ac8b6ed37f5a52ec5ae392d3, $_749742b5f62e4bb43028408449a3d308);
				}
				$_5c9a1ba56d240539e12b4b166e36ee48 = new Zend_Search_Lucene_Index_Term($this->prepareStringForQuery($_597281a31ad73195fa5c3350a184ceb5) );
				$_d3a49972ac8b6ed37f5a52ec5ae392d3 = new Zend_Search_Lucene_Search_Query_Fuzzy($_5c9a1ba56d240539e12b4b166e36ee48);
				$_531dcecef8d08920926c6289d50b6cb1->addSubquery($_d3a49972ac8b6ed37f5a52ec5ae392d3, $_749742b5f62e4bb43028408449a3d308);
			}
		}
		return $_531dcecef8d08920926c6289d50b6cb1;
	}
	private function _removeCustomWordsFromQuery($_531dcecef8d08920926c6289d50b6cb1) {
		$_de0d2450259e3a1dc3ab21795f958976 = $_531dcecef8d08920926c6289d50b6cb1;
		$_d651683e1454747a383f8847733b0735= strip_tags(trim(Mage::getStoreConfig('php4u/php4u_group/php4u_index_ignore_words', $this->getStoreId())));
		if (!empty($_d651683e1454747a383f8847733b0735)) {
			foreach (explode(',', $_d651683e1454747a383f8847733b0735) as $_2449f1250dd274d90823ff25bcee0c18) {
				$_2449f1250dd274d90823ff25bcee0c18 = trim($_2449f1250dd274d90823ff25bcee0c18);
				$_de0d2450259e3a1dc3ab21795f958976 = @preg_replace('/\b'.$_2449f1250dd274d90823ff25bcee0c18.'\b/iu',' ',$_de0d2450259e3a1dc3ab21795f958976);
			}
		}
		return $_de0d2450259e3a1dc3ab21795f958976;
	}
	private function _replaceSynonims($_531dcecef8d08920926c6289d50b6cb1) {
		if (Mage::getStoreConfigFlag('php4u/word_synonims/enabled', $this->getStoreId()) === FALSE) {
			return $_531dcecef8d08920926c6289d50b6cb1;
		}
		$_de0d2450259e3a1dc3ab21795f958976 = $_531dcecef8d08920926c6289d50b6cb1;
		$_071961855d0d8da430913a664b4b49ed= Mage::getStoreConfig('php4u/word_synonims/list', $this->getStoreId());
		if (!empty($_071961855d0d8da430913a664b4b49ed)) {
			if (!$_071961855d0d8da430913a664b4b49ed = @unserialize($_071961855d0d8da430913a664b4b49ed)) {
				return $_de0d2450259e3a1dc3ab21795f958976;
			}
			foreach ($_071961855d0d8da430913a664b4b49ed as $_15781164c2b281fe054bd89fcffd870e) {
				if (isset($_15781164c2b281fe054bd89fcffd870e['source_word']) && isset($_15781164c2b281fe054bd89fcffd870e['target_word'])) {
					if (!empty($_15781164c2b281fe054bd89fcffd870e['source_word']) && !empty($_15781164c2b281fe054bd89fcffd870e['target_word'])) {
						$_15781164c2b281fe054bd89fcffd870e['source_word'] = trim($_15781164c2b281fe054bd89fcffd870e['source_word']);
						$_15781164c2b281fe054bd89fcffd870e['source_word'] = @preg_replace("/[^a-zA-Z0-9\s\'\#\-\_]/", '', $_15781164c2b281fe054bd89fcffd870e['source_word']);
						$_15781164c2b281fe054bd89fcffd870e['target_word'] = trim($_15781164c2b281fe054bd89fcffd870e['target_word']);
						$_15781164c2b281fe054bd89fcffd870e['target_word'] = @preg_replace("/[^a-zA-Z0-9\s\'\#\-\_]/", '', $_15781164c2b281fe054bd89fcffd870e['target_word']);
						$_de0d2450259e3a1dc3ab21795f958976 = @preg_replace('/\b'.$_15781164c2b281fe054bd89fcffd870e['source_word'].'\b/iu',$_15781164c2b281fe054bd89fcffd870e['target_word'],$_de0d2450259e3a1dc3ab21795f958976);
					}
				}
			}
		}
		return $_de0d2450259e3a1dc3ab21795f958976;
	}
	private function _findSynonim($_e415e577849c7ab564451634d0eb8cb1) {
		if (Mage::getStoreConfigFlag('php4u/word_synonims/enabled', $this->getStoreId()) === FALSE) {
			return false;
		}
		$_071961855d0d8da430913a664b4b49ed= Mage::getStoreConfig('php4u/word_synonims/list', $this->getStoreId());
		if (!empty($_071961855d0d8da430913a664b4b49ed)) {
			if (!$_071961855d0d8da430913a664b4b49ed = @unserialize($_071961855d0d8da430913a664b4b49ed)) {
				return $_de0d2450259e3a1dc3ab21795f958976;
			}
			foreach ($_071961855d0d8da430913a664b4b49ed as $_15781164c2b281fe054bd89fcffd870e) {
				if (isset($_15781164c2b281fe054bd89fcffd870e['source_word']) && isset($_15781164c2b281fe054bd89fcffd870e['target_word'])) {
					if (!empty($_15781164c2b281fe054bd89fcffd870e['source_word']) && !empty($_15781164c2b281fe054bd89fcffd870e['target_word'])) {
						$_15781164c2b281fe054bd89fcffd870e['source_word'] = trim($_15781164c2b281fe054bd89fcffd870e['source_word']);
						$_15781164c2b281fe054bd89fcffd870e['source_word'] = @preg_replace("/[^a-zA-Z0-9\s\'\#\-\_]/", '', $_15781164c2b281fe054bd89fcffd870e['source_word']);
						$_15781164c2b281fe054bd89fcffd870e['target_word'] = trim($_15781164c2b281fe054bd89fcffd870e['target_word']);
						$_15781164c2b281fe054bd89fcffd870e['target_word'] = @preg_replace("/[^a-zA-Z0-9\s\'\#\-\_]/", '', $_15781164c2b281fe054bd89fcffd870e['target_word']);
						if ($_e415e577849c7ab564451634d0eb8cb1 == $_15781164c2b281fe054bd89fcffd870e['source_word']) {
							return $_15781164c2b281fe054bd89fcffd870e['target_word'];
						}
					}
					else {
					}
				}
			}
			reset($_071961855d0d8da430913a664b4b49ed);
			foreach ($_071961855d0d8da430913a664b4b49ed as $_15781164c2b281fe054bd89fcffd870e) {
				if (isset($_15781164c2b281fe054bd89fcffd870e['source_word']) && isset($_15781164c2b281fe054bd89fcffd870e['target_word'])) {
					if (!empty($_15781164c2b281fe054bd89fcffd870e['source_word']) && !empty($_15781164c2b281fe054bd89fcffd870e['target_word'])) {
						$_15781164c2b281fe054bd89fcffd870e['source_word'] = trim($_15781164c2b281fe054bd89fcffd870e['source_word']);
						$_15781164c2b281fe054bd89fcffd870e['source_word'] = @preg_replace("/[^a-zA-Z0-9\s\'\#\-\_]/", '', $_15781164c2b281fe054bd89fcffd870e['source_word']);
						$_15781164c2b281fe054bd89fcffd870e['target_word'] = trim($_15781164c2b281fe054bd89fcffd870e['target_word']);
						$_15781164c2b281fe054bd89fcffd870e['target_word'] = @preg_replace("/[^a-zA-Z0-9\s\'\#\-\_]/", '', $_15781164c2b281fe054bd89fcffd870e['target_word']);
						if ($_e415e577849c7ab564451634d0eb8cb1 == $_15781164c2b281fe054bd89fcffd870e['target_word']) {
							return $_15781164c2b281fe054bd89fcffd870e['source_word'];
						}
					}
					else {
					}
				}
			}
		}
		return false;
	}
	private function _getFulltextSign($_53be32333d4ab779012cdbd5a0eff3d7 = false) {
		if (FALSE === $_53be32333d4ab779012cdbd5a0eff3d7) {
			$_d9c5788bd0df12317883f9b813a64cd1 = Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_fulltextmode', $this->getStoreId());
		}
		else {
			$_d9c5788bd0df12317883f9b813a64cd1 = Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_no_results_fulltextmode', $this->getStoreId());
		}
		return ($_d9c5788bd0df12317883f9b813a64cd1 == 'AND' ? TRUE : NULL);
	}
	private function _tokenizeHypens($_2b86beb1e6bcf66c76c107f428c5c728) {
		if (!Mage::getStoreConfigFlag('php4u/php4u_group/php4u_split_hyphened', $this->getStoreId())) {
			return $_2b86beb1e6bcf66c76c107f428c5c728;
		}
		$_2b86beb1e6bcf66c76c107f428c5c728 = str_replace('\\', '-', $_2b86beb1e6bcf66c76c107f428c5c728);
		$_2b86beb1e6bcf66c76c107f428c5c728 = str_replace('/', '-', $_2b86beb1e6bcf66c76c107f428c5c728);
		$_5c9a1ba56d240539e12b4b166e36ee48 = '/([a-zA-Z0-9]+(\-[a-zA-Z0-9]+)+)/';
		preg_match_all($_5c9a1ba56d240539e12b4b166e36ee48, $_2b86beb1e6bcf66c76c107f428c5c728, $_7d09bc879b2d59e4cd66a56d1b1c7a96);
		if (isset($_7d09bc879b2d59e4cd66a56d1b1c7a96[0])) {
			foreach ($_7d09bc879b2d59e4cd66a56d1b1c7a96[0] as $_8082b0e6609ecffcc9f23fafd6220cc0) {
				$_91fe6c19cdcfe3f8c1d0955e113801de = explode("-", $_8082b0e6609ecffcc9f23fafd6220cc0);
				if (!empty($_91fe6c19cdcfe3f8c1d0955e113801de)) {
					$_2b86beb1e6bcf66c76c107f428c5c728 .= ' ' . implode('', $_91fe6c19cdcfe3f8c1d0955e113801de);
					$_2b86beb1e6bcf66c76c107f428c5c728 .= ' ' . implode(' ', $_91fe6c19cdcfe3f8c1d0955e113801de);
				}
			}
		}
		return $_2b86beb1e6bcf66c76c107f428c5c728;
	}
	private function _tokenizeNumbers($_2b86beb1e6bcf66c76c107f428c5c728) {
		if (!Mage::getStoreConfigFlag('php4u/php4u_group/php4u_tokenize_numbers', $this->getStoreId())) {
			return $_2b86beb1e6bcf66c76c107f428c5c728;
		}
		$_c20bc8358224fe24043a91dab4a858b0 = preg_replace('/[^0-9]/', ' ', $_2b86beb1e6bcf66c76c107f428c5c728);
		$_c20bc8358224fe24043a91dab4a858b0 = trim($_c20bc8358224fe24043a91dab4a858b0);
		$_2b86beb1e6bcf66c76c107f428c5c728 .= ' ' . $_c20bc8358224fe24043a91dab4a858b0;
		$_2b86beb1e6bcf66c76c107f428c5c728 .= ' ' . str_replace(' ', '', $_c20bc8358224fe24043a91dab4a858b0);
		return $_2b86beb1e6bcf66c76c107f428c5c728;
	}
	final protected function _findInIndex($_531dcecef8d08920926c6289d50b6cb1, $_a45299cfb87678e5090b65964c260d8e = false) {
		if ($_a45299cfb87678e5090b65964c260d8e === false) {
			$_1f784b5ccf5b95eddc71dfcda836c662 = Zend_Search_Lucene_Search_QueryParser::parse($_531dcecef8d08920926c6289d50b6cb1);
		}
		else {
			$_1f784b5ccf5b95eddc71dfcda836c662 = $_531dcecef8d08920926c6289d50b6cb1;
		}
		$_b1526cc4b285e2c8e10f38a5b6acc689 = $this->getIndex()->find($_1f784b5ccf5b95eddc71dfcda836c662);
		return $_b1526cc4b285e2c8e10f38a5b6acc689;
	}
	public function getProductsForSearchWithScore($_1627ab3fde259b3f6dab061c8720f010) {
		$_d4c14c443314a71d8979e282c218a4eb = array();
		try {
			$_954826fedb7e867f0b7972187b22a0e1 = ini_get('error_reporting');
			ini_set('error_reporting', E_ALL^E_NOTICE);
			if (!$this->_index) {
				$this->getIndex();
			}
			$_6b26723195078611b14f9989cd976f0e = Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_searchmode', $this->getStoreId());
			if ($_6b26723195078611b14f9989cd976f0e == Php4u_BlastLuceneSearch_Model_Source::LUCENE_PHRASE) {
				$_531dcecef8d08920926c6289d50b6cb1 = new Zend_Search_Lucene_Search_Query_Phrase($this->prepareStringForPhrasing($_1627ab3fde259b3f6dab061c8720f010));
			}
			else {
				$_531dcecef8d08920926c6289d50b6cb1 = new Zend_Search_Lucene_Search_Query_Boolean();
				foreach ($this->prepareStringForPhrasing($_1627ab3fde259b3f6dab061c8720f010) as $_597281a31ad73195fa5c3350a184ceb5) {
					$_749742b5f62e4bb43028408449a3d308 = $this->_getFulltextSign();
					if ($this->_findSynonim($_597281a31ad73195fa5c3350a184ceb5) !== false) {
						$_749742b5f62e4bb43028408449a3d308 = null;
						$_5e6f2db1902a43c1689a1fb03db31a0b = new Zend_Search_Lucene_Search_Query_Term(new Zend_Search_Lucene_Index_Term($this->_findSynonim($_597281a31ad73195fa5c3350a184ceb5) ));
						$_531dcecef8d08920926c6289d50b6cb1->addSubquery($_5e6f2db1902a43c1689a1fb03db31a0b, $_749742b5f62e4bb43028408449a3d308);
					}
					$_5e6f2db1902a43c1689a1fb03db31a0b = new Zend_Search_Lucene_Search_Query_Term(new Zend_Search_Lucene_Index_Term($_597281a31ad73195fa5c3350a184ceb5 ));
					$_531dcecef8d08920926c6289d50b6cb1->addSubquery($_5e6f2db1902a43c1689a1fb03db31a0b, $_749742b5f62e4bb43028408449a3d308);
				}
			}
			self::log("[SEARCH] Mode $_6b26723195078611b14f9989cd976f0e. Searching for $_1627ab3fde259b3f6dab061c8720f010 (cleaned: ".$this->prepareStringForQuery($_1627ab3fde259b3f6dab061c8720f010).")");
			$_47bb71ec810d9264f85c9d2394158aa3 = microtime(true);
			$_b1526cc4b285e2c8e10f38a5b6acc689 = $this->_findInIndex($_531dcecef8d08920926c6289d50b6cb1, FALSE);
			self::log("Max mem usage:".round(memory_get_peak_usage(true)/1024/1024,2)."Mb. Documents in index:".$this->getSize());
			$_6e6fa7040edc374345caa233a4d2432f = microtime(true) - $_47bb71ec810d9264f85c9d2394158aa3;
			$_47bb71ec810d9264f85c9d2394158aa3 = microtime(true);
			$_d4c14c443314a71d8979e282c218a4eb = $this->_processHits($_b1526cc4b285e2c8e10f38a5b6acc689);
			$_3ce3cadf7b407339245d7387d48145b7 = microtime(true) - $_47bb71ec810d9264f85c9d2394158aa3;
			ini_set('error_reporting', $_954826fedb7e867f0b7972187b22a0e1);
			self::log("[SEARCH] Lucene direct query: '{$_531dcecef8d08920926c6289d50b6cb1->__toString()}', results ".count($_d4c14c443314a71d8979e282c218a4eb)." (query: $_6e6fa7040edc374345caa233a4d2432f s, loop: $_3ce3cadf7b407339245d7387d48145b7 s).");
		}
		catch (Zend_Search_Lucene_Exception $_e2113955bd4d492e71e02138111ee195) {
			self::log("Lucene problem ".$_e2113955bd4d492e71e02138111ee195->getMessage(), Zend_Log::ERR);
		}
		return $_d4c14c443314a71d8979e282c218a4eb;
	}
	public function getProductsForSearchWithScoreForNoResults($_1627ab3fde259b3f6dab061c8720f010, $_9e5b4941badea6a39a5753c01c33027c = null) {
		if (is_null($_9e5b4941badea6a39a5753c01c33027c)) {
			$_9e5b4941badea6a39a5753c01c33027c = Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_no_results_searchmode',Mage::app()->getStore());
			self::log("[NO RESULTS SEARCH] Method from config");
		}
		elseif($_9e5b4941badea6a39a5753c01c33027c === TRUE) {
			$_9e5b4941badea6a39a5753c01c33027c = Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_no_results_searchmode',Mage::app()->getStore());
			if ($_9e5b4941badea6a39a5753c01c33027c == Php4u_BlastLuceneSearch_Model_Querytype::LUCENE_WILDCARD) {
				$_9e5b4941badea6a39a5753c01c33027c = Php4u_BlastLuceneSearch_Model_Querytype::LUCENE_FUZZY;
			}
			if ($_9e5b4941badea6a39a5753c01c33027c == Php4u_BlastLuceneSearch_Model_Querytype::LUCENE_FUZZY) {
				$_9e5b4941badea6a39a5753c01c33027c = Php4u_BlastLuceneSearch_Model_Querytype::LUCENE_WILDCARD;
			}
			self::log("[NO RESULTS SEARCH] Opposite method selected than in config");
		}
		$_d4c14c443314a71d8979e282c218a4eb = array();
		try {
			if (strlen($_1627ab3fde259b3f6dab061c8720f010) < 3 ) {
				return $_d4c14c443314a71d8979e282c218a4eb;
			}
			if (!$this->_index) {
				$this->getIndex();
			}
			if ($_9e5b4941badea6a39a5753c01c33027c == Php4u_BlastLuceneSearch_Model_Querytype::LUCENE_WILDCARD) {
				$_531dcecef8d08920926c6289d50b6cb1 = $this->prepareStringForWildcard($_1627ab3fde259b3f6dab061c8720f010);
				self::log("[NO RESULTS SEARCH] Searching for $_1627ab3fde259b3f6dab061c8720f010 ($_9e5b4941badea6a39a5753c01c33027c)");
			}
			elseif ($_9e5b4941badea6a39a5753c01c33027c == Php4u_BlastLuceneSearch_Model_Querytype::LUCENE_FUZZY) {
				$_531dcecef8d08920926c6289d50b6cb1 = $this->prepareStringForFuzzy($_1627ab3fde259b3f6dab061c8720f010);
				self::log("[NO RESULTS SEARCH] Searching for $_1627ab3fde259b3f6dab061c8720f010 ($_9e5b4941badea6a39a5753c01c33027c)");
			}
			else {
				self::log("[getProductsForSearchWithScoreForNoResults] Unknown search mode [$_9e5b4941badea6a39a5753c01c33027c] for lucene if no results", Zend_Log::ERR);
			}
			$_47bb71ec810d9264f85c9d2394158aa3 = microtime(true);
			$_b1526cc4b285e2c8e10f38a5b6acc689 = $this->_findInIndex($_531dcecef8d08920926c6289d50b6cb1, TRUE);
			$_6e6fa7040edc374345caa233a4d2432f = microtime(true) - $_47bb71ec810d9264f85c9d2394158aa3;
			$_47bb71ec810d9264f85c9d2394158aa3 = microtime(true);
			$_d4c14c443314a71d8979e282c218a4eb = $this->_processHits($_b1526cc4b285e2c8e10f38a5b6acc689);
			$_3ce3cadf7b407339245d7387d48145b7 = microtime(true) - $_47bb71ec810d9264f85c9d2394158aa3;
			self::log("[NO RESULTS SEARCH] Lucene search query: '{$_531dcecef8d08920926c6289d50b6cb1->__toString()}', results ".count($_d4c14c443314a71d8979e282c218a4eb)." (query: $_6e6fa7040edc374345caa233a4d2432f s, loop: $_3ce3cadf7b407339245d7387d48145b7 s).");
		}
		catch (Zend_Search_Lucene_Exception $_e2113955bd4d492e71e02138111ee195) {
			self::log("Lucene problem ".$_e2113955bd4d492e71e02138111ee195->getMessage(), Zend_Log::ERR);
		}
		return $_d4c14c443314a71d8979e282c218a4eb;
	}
	private function _processHits($_b1526cc4b285e2c8e10f38a5b6acc689) {
		$_d4c14c443314a71d8979e282c218a4eb = array();
		$_1e6df718aebe1a68c75bd6b0b597155b = intval(Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_limiter', $this->getStoreId()));
		self::log("[Processor] Results Limiter is set to $_1e6df718aebe1a68c75bd6b0b597155b");
		foreach ($_b1526cc4b285e2c8e10f38a5b6acc689 as $_0adff7440c5d290fa69a1f8c2c343024) {
			$_0260788546415e19a3e002563cfb4810 = $_0adff7440c5d290fa69a1f8c2c343024->getDocument();
			$_3f4c70aa028bccf7545381ee408081f4 = $_0260788546415e19a3e002563cfb4810->getFieldNames();
			$_d4c14c443314a71d8979e282c218a4eb[$_0260788546415e19a3e002563cfb4810->entity_id] = $_0adff7440c5d290fa69a1f8c2c343024->score;
			if (in_array('product_lucene_product_position', $_3f4c70aa028bccf7545381ee408081f4)) {
				if ($_0260788546415e19a3e002563cfb4810->product_lucene_product_position != 0) {
					$_05e6fb52e376689e901708e9d2cc643d = $this->_translatePosition($_0260788546415e19a3e002563cfb4810->product_lucene_product_position);
					$_d4c14c443314a71d8979e282c218a4eb[$_0260788546415e19a3e002563cfb4810->entity_id] = $_05e6fb52e376689e901708e9d2cc643d;
				}
			}
			if (Mage::getStoreConfigFlag('php4u/php4u_group/php4u_outofstock_last', $this->getStoreId()) && Mage::helper('cataloginventory')->isShowOutOfStock()) {
				$_eef684e317d00d88149196909b7f03c6 = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_0260788546415e19a3e002563cfb4810->entity_id);
				$_14f56b1f3526fe4d1278e4927a641db3 = true;
				if (is_object($_eef684e317d00d88149196909b7f03c6)) {
					$_14f56b1f3526fe4d1278e4927a641db3 = (boolean)$_eef684e317d00d88149196909b7f03c6->getIsInStock();
				}
				if (!$_14f56b1f3526fe4d1278e4927a641db3) {
					$_d4c14c443314a71d8979e282c218a4eb[$_0260788546415e19a3e002563cfb4810->entity_id] = 0;
				}
			}
			if ($_1e6df718aebe1a68c75bd6b0b597155b > 0) {
				if (count($_d4c14c443314a71d8979e282c218a4eb) >= $_1e6df718aebe1a68c75bd6b0b597155b) {
					break;
				}
			}
		}
		if (!$this->_valid) {
			self::log("[License] Limits clearead because you have no valid license installed");
			$_d4c14c443314a71d8979e282c218a4eb = array();
		}
		Mage::dispatchEvent(Php4u_BlastLuceneSearch_Model_Event::EVENT_PROCESS_HITS, array('results'=>$_d4c14c443314a71d8979e282c218a4eb));
		return $_d4c14c443314a71d8979e282c218a4eb;
	}
	public function getResultsWithScore($_87ddbbd24fb086dbd3694acd998ed20a) {
		$_1850387c816a16a2f44f65426bd58d5d = Php4u_BlastLuceneSearch_Model_Event::SEARCH_MODE_NORMAL;
		$_87ddbbd24fb086dbd3694acd998ed20a = $this->_removeCustomWordsFromQuery($_87ddbbd24fb086dbd3694acd998ed20a);
		$_a0764124f9fafd9237a5ea9ebf7a7c76 = $this->getProductsForSearchWithScore($_87ddbbd24fb086dbd3694acd998ed20a);
		$_08a4ba7d9a385a43c31d69ed50522dcf = Mage::getStoreConfig('php4u/php4u_group/php4u_no_results_append_min_qty',Mage::app()->getStore());
		$_59f45a2d63b96f3dd8b78e504ab39376 = Mage::getStoreConfig('php4u/php4u_group/php4u_no_results_second_append_min_qty',Mage::app()->getStore());
		if (empty($_a0764124f9fafd9237a5ea9ebf7a7c76) && Mage::getStoreConfigFlag('php4u/php4u_group/php4u_no_results_enabled',$this->getStoreId())) {
			$_569f74676b1bc67f93ee8c2aec004568 = Mage::getStoreConfig('php4u/php4u_group/php4u_chars2trim',Mage::app()->getStore());
			if (strlen($_569f74676b1bc67f93ee8c2aec004568) > 20) {
				$_569f74676b1bc67f93ee8c2aec004568 = substr($_569f74676b1bc67f93ee8c2aec004568, 0, 20);
			}
			if (strlen($_569f74676b1bc67f93ee8c2aec004568)>0) {
				self::log("[NO RESULTS SEARCH] Trimming [$_569f74676b1bc67f93ee8c2aec004568] from '$_87ddbbd24fb086dbd3694acd998ed20a'");
				for ($_2ded5c622c934a71410e4681cfff8291=0;$_2ded5c622c934a71410e4681cfff8291<strlen($_569f74676b1bc67f93ee8c2aec004568);
				$_2ded5c622c934a71410e4681cfff8291++) {
					$_87ddbbd24fb086dbd3694acd998ed20a = str_ireplace($_569f74676b1bc67f93ee8c2aec004568[$_2ded5c622c934a71410e4681cfff8291], '', $_87ddbbd24fb086dbd3694acd998ed20a);
				}
				$_a0764124f9fafd9237a5ea9ebf7a7c76 = $this->getProductsForSearchWithScore($_87ddbbd24fb086dbd3694acd998ed20a);
			}
		}
		$_a6917546c281ee0baa0211b3368bdf06 = count($_a0764124f9fafd9237a5ea9ebf7a7c76) < $_08a4ba7d9a385a43c31d69ed50522dcf;
		if ( (empty($_a0764124f9fafd9237a5ea9ebf7a7c76) || $_a6917546c281ee0baa0211b3368bdf06) && Mage::getStoreConfigFlag('php4u/php4u_group/php4u_no_results_enabled',$this->getStoreId())) {
			$_4691659b201d69badccfa090af4d7434 = $this->getProductsForSearchWithScoreForNoResults($_87ddbbd24fb086dbd3694acd998ed20a, null);
			$_1850387c816a16a2f44f65426bd58d5d = Php4u_BlastLuceneSearch_Model_Event::SEARCH_MODE_FUZZY_WILDCARD;
			if ($_a6917546c281ee0baa0211b3368bdf06) {
				self::log("[NO RESULTS SEARCH] Appending FIRST as is lower than $_08a4ba7d9a385a43c31d69ed50522dcf");
				$this->_mergeResults($_4691659b201d69badccfa090af4d7434, $_a0764124f9fafd9237a5ea9ebf7a7c76);
			}
			else {
				self::log("[NO RESULTS SEARCH] Replacing with FIRST ATTEMPT");
				$_a0764124f9fafd9237a5ea9ebf7a7c76 = $_4691659b201d69badccfa090af4d7434;
			}
		}
		$_3f51f83f77ed52c2ee06f0e3643cdbdf = count($_a0764124f9fafd9237a5ea9ebf7a7c76) < $_59f45a2d63b96f3dd8b78e504ab39376;
		if ( (empty($_a0764124f9fafd9237a5ea9ebf7a7c76) || $_3f51f83f77ed52c2ee06f0e3643cdbdf) && Mage::getStoreConfigFlag('php4u/php4u_group/php4u_no_results_second_enabled',$this->getStoreId()) && Mage::getStoreConfigFlag('php4u/php4u_group/php4u_no_results_enabled',$this->getStoreId())) {
			$_e12a9a06612fd2cb36a0c537ac00a82b = $this->getProductsForSearchWithScoreForNoResults($_87ddbbd24fb086dbd3694acd998ed20a, true);
			$_1850387c816a16a2f44f65426bd58d5d = Php4u_BlastLuceneSearch_Model_Event::SEARCH_MODE_FUZZY_WILDCARD;
			if ($_3f51f83f77ed52c2ee06f0e3643cdbdf) {
				self::log("[NO RESULTS SEARCH] Appending SECOND as is lower than $_59f45a2d63b96f3dd8b78e504ab39376");
				$this->_mergeResults($_e12a9a06612fd2cb36a0c537ac00a82b, $_a0764124f9fafd9237a5ea9ebf7a7c76);
			}
			else {
				self::log("[NO RESULTS SEARCH] Replacing with SECOND ATTEMPT");
				$_a0764124f9fafd9237a5ea9ebf7a7c76 = $_e12a9a06612fd2cb36a0c537ac00a82b;
			}
		}
		Mage::getModel('blastlucenesearch/event')->throwSearchDone($_1850387c816a16a2f44f65426bd58d5d);
		return $_a0764124f9fafd9237a5ea9ebf7a7c76;
	}
	protected function _mergeResults(array $_8462c38dc46e459a560be2413fb4b704, array &$_19ca0ec67e22ccca399dd950bb7da500) {
		foreach ($_8462c38dc46e459a560be2413fb4b704 as $_506d7ca1425a89cd2fb52f63efca0cf5 => $_f4d3ce46f634e77ce23f96c65e424474) {
			if (!isset($_19ca0ec67e22ccca399dd950bb7da500[$_506d7ca1425a89cd2fb52f63efca0cf5])) {
				$_19ca0ec67e22ccca399dd950bb7da500[$_506d7ca1425a89cd2fb52f63efca0cf5] = $_f4d3ce46f634e77ce23f96c65e424474 * 0.5;
			}
		}
	}
	public function getResultsForApi($_87ddbbd24fb086dbd3694acd998ed20a, $_c1ca93eaa23ff172f14a57ff40842925 = '300') {
		Mage::app()->setCurrentStore($this->getStoreId());
		$_87ddbbd24fb086dbd3694acd998ed20a = $this->_removeCustomWordsFromQuery($_87ddbbd24fb086dbd3694acd998ed20a);
		$_1c8c60a34605501165604df86fb932b2 = array();
		$_a0764124f9fafd9237a5ea9ebf7a7c76 = $this->getResultsWithScore($_87ddbbd24fb086dbd3694acd998ed20a);
		if (empty($_a0764124f9fafd9237a5ea9ebf7a7c76)) {
			return $_1c8c60a34605501165604df86fb932b2;
		}
		$_13766f5f245587c0c606f17149bce92a = Mage::getModel('catalog/product')->getCollection() ->addStoreFilter($this->getStoreId()) ->addAttributeToSelect('name') ->addAttributeToSelect('image') ->addMinimalPrice() ->addFinalPrice() ->addFieldToFilter('entity_id',array('in'=>array_keys($_a0764124f9fafd9237a5ea9ebf7a7c76))) ;
		foreach ($_13766f5f245587c0c606f17149bce92a as $_4d4786c90bc462bbef64b33271398a88) {
			if (!$_4d4786c90bc462bbef64b33271398a88) {
				continue;
			}
			$_1c8c60a34605501165604df86fb932b2[$_a0764124f9fafd9237a5ea9ebf7a7c76[$_4d4786c90bc462bbef64b33271398a88->getId()] * 100000] = array( 'product_id' => $_4d4786c90bc462bbef64b33271398a88->getId(), 'sku' => $_4d4786c90bc462bbef64b33271398a88->getSku(), 'name' => $_4d4786c90bc462bbef64b33271398a88->getName(), 'set' => $_4d4786c90bc462bbef64b33271398a88->getAttributeSetId(), 'type' => $_4d4786c90bc462bbef64b33271398a88->getTypeId(), 'category_ids' => $_4d4786c90bc462bbef64b33271398a88->getCategoryIds(), 'full_url' => $_4d4786c90bc462bbef64b33271398a88->getUrlInStore(), 'image_url' => (string)Mage::helper('catalog/image')->init($_4d4786c90bc462bbef64b33271398a88, 'image')->resize($_c1ca93eaa23ff172f14a57ff40842925), 'price' => $this->_getPrice($_4d4786c90bc462bbef64b33271398a88) == 0 ? $_4d4786c90bc462bbef64b33271398a88->getPrice() : $this->_getPrice($_4d4786c90bc462bbef64b33271398a88), 'final_price' => $_4d4786c90bc462bbef64b33271398a88->getFinalPrice(), );
		}
		krsort($_1c8c60a34605501165604df86fb932b2);
		return $_1c8c60a34605501165604df86fb932b2;
	}
	private function _getPrice($_4d4786c90bc462bbef64b33271398a88) {
		if ($_4d4786c90bc462bbef64b33271398a88->getTypeId() != 'bundle') {
			return 0;
		}
		list($_minimalPrice, $_maximalPrice) = $_4d4786c90bc462bbef64b33271398a88->getPriceModel()->getPrices($_4d4786c90bc462bbef64b33271398a88);
		$_weeeTaxAmount = 0;
		$_minimalPriceTax = Mage::helper('tax')->getPrice($_4d4786c90bc462bbef64b33271398a88, $_minimalPrice);
		$_minimalPriceInclTax = Mage::helper('tax')->getPrice($_4d4786c90bc462bbef64b33271398a88, $_minimalPrice, true);
		if ($_4d4786c90bc462bbef64b33271398a88->getPriceType() == 1) {
			$_weeeTaxAmount = Mage::helper('weee')->getAmount($_4d4786c90bc462bbef64b33271398a88);
			if ($_weeeTaxAmount && Mage::helper('weee')->typeOfDisplay($_4d4786c90bc462bbef64b33271398a88, array(0, 1, 4))) {
				$_minimalPriceTax += $_weeeTaxAmount;
				$_minimalPriceInclTax += $_weeeTaxAmount;
			}
			if ($_weeeTaxAmount && Mage::helper('weee')->typeOfDisplay($_4d4786c90bc462bbef64b33271398a88, 2)) {
				$_minimalPriceInclTax += $_weeeTaxAmount;
			}
			if (Mage::helper('weee')->typeOfDisplay($_4d4786c90bc462bbef64b33271398a88, array(1,2,4))) {
				$_weeeTaxAttributes = Mage::helper('weee')->getProductWeeeAttributesForDisplay($_4d4786c90bc462bbef64b33271398a88);
			}
		}
		return $_minimalPriceInclTax;
	}
	public static function optimizeIndexForAllStores() {
		foreach (Mage::app()->getStores(false) as $_6f80c34971a9762ddb1d1579fe5576c9) {
			Mage::getSingleton('blastlucenesearch/blastlucenesearch')->setStoreId($_6f80c34971a9762ddb1d1579fe5576c9->getId())->optimizeIndex();
		}
	}
	public function optimizeIndex() {
		$_47bb71ec810d9264f85c9d2394158aa3 = microtime(true);
		self::log("[OPTIMIZE] Store [".$this->getStoreId()."] Lucene index optimisation started");
		$_1c8c60a34605501165604df86fb932b2 = $this->getIndex()->optimize();
		$_74f6fc6c75d8311391fa78d8e249de7a = microtime(true) - $_47bb71ec810d9264f85c9d2394158aa3;
		self::log("[OPTIMIZE] [".$this->getStoreId()."] Lucene index optimisation done ($_74f6fc6c75d8311391fa78d8e249de7a secs). Max mem usage:".round(memory_get_peak_usage(true)/1024/1024,2)."Mb. Documents in index:".$this->getSize());
		return $_1c8c60a34605501165604df86fb932b2;
	}
	public function getSize() {
		return $this->getIndex()->numDocs();
	}
	public function fixIndexForQuery($_531dcecef8d08920926c6289d50b6cb1, array $_a0764124f9fafd9237a5ea9ebf7a7c76) {
		$_5e4dc48878a719586fd1017a3094b0c5 = array();
		self::log("[FIXER] Found products which were removed from store but not from index");
		if (!empty($_a0764124f9fafd9237a5ea9ebf7a7c76)) {
			foreach ($_a0764124f9fafd9237a5ea9ebf7a7c76 as $_684842bcb895b7bec57b6015cc62a5fb => $_f4d3ce46f634e77ce23f96c65e424474) {
				$_4d4786c90bc462bbef64b33271398a88 = Mage::getModel('catalog/product')->load($_684842bcb895b7bec57b6015cc62a5fb);
				if ($_4d4786c90bc462bbef64b33271398a88->getData('entity_id')) {
					$_5e4dc48878a719586fd1017a3094b0c5[] = "({$_531dcecef8d08920926c6289d50b6cb1->getId()}, {$_684842bcb895b7bec57b6015cc62a5fb}, {$_f4d3ce46f634e77ce23f96c65e424474})";
				}
				else {
					$this->removeProductFromIndex($_684842bcb895b7bec57b6015cc62a5fb, false);
					self::log("[FIXER] Removing product $_684842bcb895b7bec57b6015cc62a5fb from index");
				}
			}
			if (is_array($_5e4dc48878a719586fd1017a3094b0c5)) {
				$_5e4dc48878a719586fd1017a3094b0c5 = implode(',', $_5e4dc48878a719586fd1017a3094b0c5);
				$_58096a6bc9058e927b9bde03d5f66f12 = "REPLACE INTO `{$this->getTable('catalogsearch/result')}` VALUES {$_5e4dc48878a719586fd1017a3094b0c5}";
				try {
					$this->_getAdapter()->query($_58096a6bc9058e927b9bde03d5f66f12);
				}
				catch (Exception $_fbece582ed7c0bbfd80e779288ab13e9) {
					return false;
				}
			}
		}
		return true;
	}
	public function log($_8da02e80e2492eca94126ae6895be27c, $_e5bc01adb483ba8e01dd9b992a63e1fe=null) {
		if (FALSE === Mage::getStoreConfigFlag('php4u/php4u_group/log_enabled', $this->getStoreId())) {
			return;
		}
		if (isset($_SERVER['REMOTE_ADDR'])) {
			$_8da02e80e2492eca94126ae6895be27c = $_SERVER['REMOTE_ADDR']. "|".$_8da02e80e2492eca94126ae6895be27c;
		}
		$_ac9a7a79a6b3cf129a91abe93eebb768 = Mage::getModel('core/session')->getEncryptedSessionId();
		if (!empty($_ac9a7a79a6b3cf129a91abe93eebb768)) {
			$_8da02e80e2492eca94126ae6895be27c = $_ac9a7a79a6b3cf129a91abe93eebb768. "|".$_8da02e80e2492eca94126ae6895be27c;
		}
		Mage::log($_8da02e80e2492eca94126ae6895be27c, $_e5bc01adb483ba8e01dd9b992a63e1fe, self::$_logname);
	}
	public function isMagentoVerLess14() {
		return (version_compare(Mage::getVersion(), '1.4.1.9') < 0);
	}
	public function isMagentoVer16orMore() {
		return (version_compare(Mage::getVersion(), '1.5.9.9') > 0);
	}
	public function getVersion() {
		$_23bfbfaf9c448e0792ee7056f9cfbf89 = dirname(__FILE__).'/../etc/config.xml';
		if (is_file($_23bfbfaf9c448e0792ee7056f9cfbf89)) {
			$_1ba1a3e727a6ab749af029ed32eab42e = @simplexml_load_file($_23bfbfaf9c448e0792ee7056f9cfbf89);
			return strval($_1ba1a3e727a6ab749af029ed32eab42e->modules->Php4u_BlastLuceneSearch->version);
		}
		return '0.0.0';
	}
	public function getProductTableIntName() {
		$_5c02043921f935ad01fa2730adcd5a34 = Mage::getSingleton('core/resource')->getTableName('catalog_product_entity_int');
		return $_5c02043921f935ad01fa2730adcd5a34;
	}
	public function getProductTableName() {
		$_5c02043921f935ad01fa2730adcd5a34 = Mage::getSingleton('core/resource')->getTableName('catalog_product_entity');
		return $_5c02043921f935ad01fa2730adcd5a34;
	}
	private final function _prepareApp() {
		if (empty(self::$_lic)) {
			$_5d0fd7661ca9099a8db4c05ad4896a11 = parse_url(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));
			$_7b426c5b5b5b3868fd0c4bbbc2a99478 = array( 'HTTP_HOST' => $_5d0fd7661ca9099a8db4c05ad4896a11['host'], 'SERVER_NAME' => $_5d0fd7661ca9099a8db4c05ad4896a11['host'], );
			if (isset($_SERVER['SERVER_ADDR'])) {
				$_7b426c5b5b5b3868fd0c4bbbc2a99478['SERVER_ADDR'] = $_SERVER['SERVER_ADDR'];
			}
			if (!defined('COMPILER_INCLUDE_PATH')) {
				$_55574d3e4430ed2087ec849f8a72662a = dirname(__FILE__).'/lib/Lucene.lib.php';
				$_acc750ac528070ebc970dc68de405c04 = dirname(__FILE__).'/lib/Lucene.app.php';
			}
			else {
				$_55574d3e4430ed2087ec849f8a72662a = dirname(__FILE__).'/Php4u_BlastLuceneSearch_Model_lib_Lucene.lib.php';
				$_acc750ac528070ebc970dc68de405c04 = dirname(__FILE__).'/Php4u_BlastLuceneSearch_Model_lib_Lucene.app.php';
			}
			if(@!file_exists($_55574d3e4430ed2087ec849f8a72662a) || @!is_file($_55574d3e4430ed2087ec849f8a72662a) || @!file_exists($_acc750ac528070ebc970dc68de405c04) || @!is_file($_acc750ac528070ebc970dc68de405c04)) throw new Exception("Blast Search Lucene module corrupted / some files missing");
			require_once($_55574d3e4430ed2087ec849f8a72662a);
			require_once($_acc750ac528070ebc970dc68de405c04);
			$this->application = new license_application(null, false, true, true, true);
			$this->application->set_server_vars($_7b426c5b5b5b3868fd0c4bbbc2a99478);
			self::$_lic =Array ("RESULT"=>"OK") ;
			$this->application->make_secure();
		}
		if (!$this->isLicValid()) {
			self::log(base64_decode('TGljZW5zZSBlcnJvcg==').': '.$this->getLicResultText());
			if (Mage::getStoreConfig('php4u/php4u_group/php4u_lucene_enabled')) {
				$this->_getAdminSession()->addError(Mage::helper('php4u')->__('Blast Search Lucene Error: '.$this->getLicResultText()));
				$this->_getSession()->addError(Mage::helper('php4u')->__('Blast Search Lucene Error: Search is not working properly.'));
			}
			return false;
		}
		unset($_01f2471e16049e17b17b2e0b8ea66c87);
		return true;
	}
	private final function _getLicData() {
		return Mage::getStoreConfig(base64_decode('cGhwNHUvbGljZW5zZS9rZXk='),$this->getStoreId());
	}
	public final function getLicResult() {
		if (isset(self::$_lic['RESULT'])) {
			return self::$_lic['RESULT'];
		}
		else {
			return array();
		}
	}
	public final function getLicResultText() {
		return $this->_translate($this->getLicResult());
	}
	protected function _getAdminSession() {
		return Mage::getSingleton('adminhtml/session');
	}
	protected function _getSession() {
		return Mage::getSingleton('customer/session');
	}
	final public function isLicValid() {
		if (empty(self::$_lic) || !isset(self::$_lic['RESULT'] )) {
			$this->_valid = false;
			return false;
		}
		if (isset(self::$_lic['RESULT'])) {
			return (self::$_lic['RESULT'] == base64_decode('T0s='));
		}
		return false;
	}
	public final function getLicInfo() {
		$_5a46660f468b88d281d370fea8f3fc2e = '<p>'.$this->getLicResultText().'</p>';
		if (isset(self::$_lic['DATE']['HUMAN']['START'])) $_5a46660f468b88d281d370fea8f3fc2e .= '<p>Start date: <strong>'.self::$_lic['DATE']['HUMAN']['START'].'</strong></p>';
		if (isset(self::$_lic['DATA']['type']) && self::$_lic['DATA']['type'] === base64_decode('dHJpYWw=')) {
			$_5d0fd7661ca9099a8db4c05ad4896a11 = parse_url(Mage::getBaseUrl (Mage_Core_Model_Store::URL_TYPE_WEB));
			$_5a46660f468b88d281d370fea8f3fc2e .= '<p>Licenced domain: <strong>'.$_5d0fd7661ca9099a8db4c05ad4896a11['host'].'</strong> (7-days trial)</p>';
			if (isset(self::$_lic['DATE']['HUMAN']['END'])) $_5a46660f468b88d281d370fea8f3fc2e .= '<p>Expire: <strong>'.self::$_lic['DATE']['HUMAN']['END'].'</strong></p>';
			$_5a46660f468b88d281d370fea8f3fc2e .= '<p>Please do replace with proper license for your main domain. One could be sent to you via email or it can be found on order confirmation email</p>';
		}
		else {
			if (isset(self::$_lic['SERVER']['DOMAIN'])) {
				$_5a46660f468b88d281d370fea8f3fc2e .= '<p>Licenced domain: http(s)://<strong>'.self::$_lic['SERVER']['DOMAIN'].'</strong></p>';
				$_5a46660f468b88d281d370fea8f3fc2e .= '<p>License allows you to use it on unlimited number of subdomains of your main domain <br/>(as example: http(s)://<strong>anysubdomain.'.self::$_lic['SERVER']['DOMAIN'].'</strong> <br/>or development/staging/local domains <br/>(domains such as somedomain.local, dev.domain.com, stg.domain.com, test.domain.com)</p>';
			}
		}
		return $_5a46660f468b88d281d370fea8f3fc2e;
	}
	public final function _translate($_ece3632cd7627d56148020253dae1f26) {
		$_2a804525659796bf76d419b59fa461ce['OK'] = "The License Key supplied is valid.";
		$_2a804525659796bf76d419b59fa461ce['TMINUS'] = "The License Key supplied you are using with this application has not yet entered its valid period.";
		$_2a804525659796bf76d419b59fa461ce['EXPIRED'] = "The License Key supplied you are using with this application has expired and is no longer valid.";
		$_2a804525659796bf76d419b59fa461ce['ILLEGAL'] = "The License Key is not valid for this server. This means that you cannot make further use of this application untill you purchase a valid key. HOWEVER, if you have you have purchased a valid key and you get this message in error, please contact the applications reseller.";
		$_2a804525659796bf76d419b59fa461ce['ILLEGAL_LOCAL'] = "This application can not be run on the localhost. The application can only be run under a valid domain.";
		$_2a804525659796bf76d419b59fa461ce['INVALID'] = "The License Key is invalid. This means that your License Key file has become corrupted. Please replace license with a copy of the original license. If you do not still have a copy of the original license please contact the applications reseller.";
		$_2a804525659796bf76d419b59fa461ce['EMPTY'] = "The License Key is empty.";
		if (isset($_2a804525659796bf76d419b59fa461ce[$_ece3632cd7627d56148020253dae1f26])) {
			return $_2a804525659796bf76d419b59fa461ce[$_ece3632cd7627d56148020253dae1f26];
		}
		return null;
	}
}