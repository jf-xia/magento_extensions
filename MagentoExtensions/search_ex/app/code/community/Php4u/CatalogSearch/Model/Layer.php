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
class Php4u_CatalogSearch_Model_Layer extends Mage_CatalogSearch_Model_Layer { public function prepareProductCollection($_a4b0e4c8b682ea016028b39b7e6809a4) { parent::prepareProductCollection($_a4b0e4c8b682ea016028b39b7e6809a4); $_ddee2f916392783c19d19f471c96d0f0 = intval(Mage::app()->getRequest()->getParam('_cat_id')); if ($_ddee2f916392783c19d19f471c96d0f0 > 0) { $_dfc5498c9cfb98274ffc163bdbe801df = Mage::getModel('catalog/category')->load($_ddee2f916392783c19d19f471c96d0f0); if ($_dfc5498c9cfb98274ffc163bdbe801df !== false) { $_a4b0e4c8b682ea016028b39b7e6809a4->addCategoryFilter($_dfc5498c9cfb98274ffc163bdbe801df,true); } } } }
?>

