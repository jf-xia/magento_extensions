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
			 class Php4u_BlastLuceneSearch_Block_System_Config_Form_Field_Booster extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract { protected $_attributes = array(); protected $_productEntityId = null; public function __construct() { $this->addColumn('product_attribute', array( 'label' => Mage::helper('adminhtml')->__('Product attribute'), 'style' => 'width:250px', )); $this->addColumn('search_boost', array( 'label' => Mage::helper('adminhtml')->__('Search boost'), 'style' => 'width:250px', )); $this->_addAfter = false; $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add new booster'); parent::__construct(); $this->setTemplate('blastsearchlucene/system/config/form/field/array.phtml'); } public function getProductAttributeList() { $_0be23bfa1644107ecba9aa8536cdc3c7 = Mage::getResourceModel('catalog/product_attribute_collection') ->addHasOptionsFilter() ->addIsSearchableFilter() ->setOrder('main_table.frontend_label', 'asc') ->load(); foreach ($_0be23bfa1644107ecba9aa8536cdc3c7 as $_8cf3f69368c8690ab123d539cbf1fb74) { $this->_attributes[$_8cf3f69368c8690ab123d539cbf1fb74->getData('attribute_id')] = array( 'label' => $_8cf3f69368c8690ab123d539cbf1fb74->getData('frontend_label'), 'value' => $_8cf3f69368c8690ab123d539cbf1fb74->getData('attribute_code'), ); } return $this->_attributes; } protected function _renderCellTemplate($_3ccfb25f0780f59bb608a1dbb1d669d5) { if (empty($this->_columns[$_3ccfb25f0780f59bb608a1dbb1d669d5])) { throw new Exception('Wrong column name specified.'); } if($_3ccfb25f0780f59bb608a1dbb1d669d5 != 'product_attribute') { return parent::_renderCellTemplate($_3ccfb25f0780f59bb608a1dbb1d669d5); } $_be39733f6641f8ad8f0537ba4d9c7e9e = $this->_columns[$_3ccfb25f0780f59bb608a1dbb1d669d5]; $_43f5c2c104e2c605f2ff3ab70879c345 = $this->getElement()->getName() . '[#{_id}][' . $_3ccfb25f0780f59bb608a1dbb1d669d5 . ']'; $_ca1bcc3ac7b612ac8b4343929d2783bc = '<select name="'.$_43f5c2c104e2c605f2ff3ab70879c345.'">'; if($_3ccfb25f0780f59bb608a1dbb1d669d5 == 'product_attribute') { foreach ($this->getProductAttributeList() as $_f059d3c74c3cd482638d6b16148469ec) { $_ca1bcc3ac7b612ac8b4343929d2783bc .= '<option value="'.$_f059d3c74c3cd482638d6b16148469ec['value'].'">'.$_f059d3c74c3cd482638d6b16148469ec['label'].'</option>'; } } $_ca1bcc3ac7b612ac8b4343929d2783bc .= '</select>'; return $_ca1bcc3ac7b612ac8b4343929d2783bc; } }
?>

