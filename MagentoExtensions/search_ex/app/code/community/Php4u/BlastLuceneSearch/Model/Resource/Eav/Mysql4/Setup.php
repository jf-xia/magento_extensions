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
class Php4u_BlastLuceneSearch_Model_Resource_Eav_Mysql4_Setup extends Mage_Eav_Model_Entity_Setup { public function getLuceneAttribute() { return array( 'group' => 'Blast Search Lucene', 'type' => 'int', 'backend' => '', 'frontend' => '', 'label' => 'Lucene Indexed?', 'input' => 'select', 'class' => '', 'source' => 'eav/entity_attribute_source_boolean', 'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE, 'visible' => false, 'required' => false, 'user_defined' => true, 'default' => 0, 'searchable' => false, 'filterable' => false, 'comparable' => false, 'visible_on_front' => false, 'unique' => false, ); } public function getLucenePositionAttribute() { return array( 'group' => 'Blast Search Lucene', 'type' => 'int', 'backend' => '', 'frontend' => '', 'label' => 'Position in search results', 'input' => 'select', 'class' => '', 'source' => 'blastlucenesearch/position', 'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE, 'visible' => true, 'required' => false, 'user_defined' => true, 'default' => 0, 'searchable' => true, 'filterable' => false, 'comparable' => false, 'visible_on_front' => false, 'unique' => false, ); } }
?>

