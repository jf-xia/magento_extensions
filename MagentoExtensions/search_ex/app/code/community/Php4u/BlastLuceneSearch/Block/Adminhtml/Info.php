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
			 class Php4u_BlastLuceneSearch_Block_Adminhtml_Info extends Mage_Adminhtml_Block_System_Config_Form_Field { protected function _getElementHtml(Varien_Data_Form_Element_Abstract $_29bd2d4efbcab4acd0dbb7427015bee8) { $_e2f09c6e1baeb93120e8a56faec2fd93 = Mage::getModel('core/store')->load(Mage::app()->getRequest()->getParam('store')); return Mage::getModel('blastlucenesearch/blastlucenesearch', array('store' => $_e2f09c6e1baeb93120e8a56faec2fd93))->getLicInfo(); } }
?>

