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
class Php4u_BlastLuceneSearch_Model_Position extends Varien_Object { const LUCENE_POS_TOP = 100; const LUCENE_POS_DEFAULT = 0; const LUCENE_POS_BOTTOM = 10; public function getAllOptions() { if (is_null($this->_options)) { $this->_options = array( array( 'label' => Mage::helper('php4u')->__('Top'), 'value' => self::LUCENE_POS_TOP ), array( 'label' => Mage::helper('php4u')->__('Default'), 'value' => self::LUCENE_POS_DEFAULT ), array( 'label' => Mage::helper('php4u')->__('Bottom'), 'value' => self::LUCENE_POS_BOTTOM ), ); } return $this->_options; } public function getOptionText($_830fc8f4211fdafb91112d265c3e09cd) { $_cb51cd92d4d144d35d61bce4ba166be4 = $this->getAllOptions(); foreach ($_cb51cd92d4d144d35d61bce4ba166be4 as $_b132b1fc0c28b978f23484d91920a326) { if ($_b132b1fc0c28b978f23484d91920a326['value'] == $_830fc8f4211fdafb91112d265c3e09cd) { return $_b132b1fc0c28b978f23484d91920a326['label']; } } return Mage::helper('php4u')->__('Default'); } }
?>

