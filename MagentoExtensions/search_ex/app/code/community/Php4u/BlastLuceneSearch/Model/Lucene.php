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
class Php4u_BlastLuceneSearch_Model_Lucene extends Mage_Core_Model_Abstract { protected function _construct() { $this->_init('blastlucenesearch/lucene'); } protected function getIndexer() { return Mage::getSingleton('blastlucenesearch/mysql4_lucene'); } public function rebuildIndex($_fd16782ebf94d4f35083512faf6da250 = null, $_7d8672bd68426d174aa59658c81bea8d = null) { $this->getIndexer()->rebuildIndex($_fd16782ebf94d4f35083512faf6da250, $_7d8672bd68426d174aa59658c81bea8d); return $this; } public function cleanIndex($_fd16782ebf94d4f35083512faf6da250 = null, $_7d8672bd68426d174aa59658c81bea8d = null) { $this->getIndexer()->cleanIndex($_fd16782ebf94d4f35083512faf6da250, $_7d8672bd68426d174aa59658c81bea8d); return $this; } public function resetSearchResults() { $this->getIndexer()->resetSearchResults(); return $this; } public function prepareResult($_b628cd6f0197a60484e3a4feaa97cf21 = null) { if (!$_b628cd6f0197a60484e3a4feaa97cf21 instanceof Mage_CatalogSearch_Model_Query) { $_b628cd6f0197a60484e3a4feaa97cf21 = Mage::helper('catalogSearch')->getQuery(); } $_54006209cc1edb8ac703b784d106a699 = Mage::helper('catalogSearch')->getQueryText(); if ($_b628cd6f0197a60484e3a4feaa97cf21->getSynonimFor()) { $_54006209cc1edb8ac703b784d106a699 = $_b628cd6f0197a60484e3a4feaa97cf21->getSynonimFor(); } $this->getIndexer()->prepareResult($this, $_54006209cc1edb8ac703b784d106a699, $_b628cd6f0197a60484e3a4feaa97cf21); return $this; } public function getSearchType($_fd16782ebf94d4f35083512faf6da250 = null) { return Mage::getStoreConfig(self::XML_PATH_CATALOG_SEARCH_TYPE, $_fd16782ebf94d4f35083512faf6da250); } }
?>

