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
			 class Php4u_BlastLuceneSearch_Block_System_Config_Form_Field_Variables extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract { public function __construct() { $this->addColumn('source_word', array( 'label' => Mage::helper('adminhtml')->__('Word to replace'), 'style' => 'width:250px', )); $this->addColumn('target_word', array( 'label' => Mage::helper('adminhtml')->__('Value'), 'style' => 'width:250px', )); $this->_addAfter = false; $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add new synonim'); parent::__construct(); } }
?>

