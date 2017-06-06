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
class Php4u_Adminhtml_Block_System_Cache_Edit extends Mage_Adminhtml_Block_System_Cache_Edit {
    public function getCatalogData() { 
        $_13ede4625054d2658a6838a4eddd02c4 = parent::getCatalogData(); 
        $_13ede4625054d2658a6838a4eddd02c4['rebuild_search_index_lucene'] = array( 
            'label' => Mage::helper('adminhtml')->__('Search Index Lucene'), 
            'buttons' => array( array( 'name' => 'rebuild_search_index_lucene', 
            'action' => Mage::helper('adminhtml')->__('Rebuild'), ) ), ); 
        return $_13ede4625054d2658a6838a4eddd02c4; 
    } 
}
?>

