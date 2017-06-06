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
	 class Php4u_BlastLuceneSearch_Block_Dym extends Mage_CatalogSearch_Block_Result { protected function _construct() { parent::_construct(); $this->addData(array( 'cache_lifetime' => 3600, 'cache_tags' => array(Php4u_BlastLuceneSearch_Model_Dym::CACHE_TAG), 'cache_key' => $this->_genCacheKey() )); } protected function _genCacheKey() { $_a7207c337f852dc4b7624972721daa54 = "php4u_lucene_dym". md5($this->getSearchQuery().Mage::getStoreConfigFlag('php4u/dym/enabled')); return $_a7207c337f852dc4b7624972721daa54; } public function getSearchQuery() { return Mage::helper('catalogsearch')->getQueryText(); } public function getDymString() { $_a5ccc4776f2d1c27e9f9f93c09b83390 = Mage::getModel('blastlucenesearch/dym')->setStoreId(Mage::app()->getStore()->getId()); $_c9cc0d0e0c523bf13b69ddde83284a5a = $_a5ccc4776f2d1c27e9f9f93c09b83390->getSuggestions($this->getSearchQuery()); if ($_c9cc0d0e0c523bf13b69ddde83284a5a) { return $this->helper('php4u')->__('Do you mean'). ' "<a href="'.Mage::getUrl('catalogsearch/result', array('_query' => array('q' => $_c9cc0d0e0c523bf13b69ddde83284a5a), '_secure' => Mage::app()->getStore()->isCurrentlySecure() ) ).'">'.$this->urlEscape($_c9cc0d0e0c523bf13b69ddde83284a5a).'</a>"?'; } return ''; } public function isDymAvailable() { return (bool) ($this->getDymString() != ''); } }
?>

