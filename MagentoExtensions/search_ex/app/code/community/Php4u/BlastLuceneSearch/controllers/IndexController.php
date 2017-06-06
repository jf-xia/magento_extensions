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
			 require_once "Mage/Cms/controllers/IndexController.php"; class Php4u_BlastLuceneSearch_IndexController extends Mage_Cms_IndexController { public function noRouteAction($_3b51e4b5dfe7c254ec475a5fa3befd56 = null) { if (!Mage::getStoreConfigFlag('php4u/lucene404/enabled')) { return parent::noRouteAction(); } $_0bbad16bbfba005ee653e95c0ccc3e6a = Mage::helper('php4u')->parseUrl(); $_5461adfd1c55fd8f1c6c3dca5d516497 = Mage::helper('catalogsearch'); $_ca64fdd45775e725bf94e9c8115c126e = Mage::getModel('blastlucenesearch/dym')->setStoreId(Mage::app()->getStore()->getId()); if ($_0bbad16bbfba005ee653e95c0ccc3e6a) { $_6858a7e083ad1067092d78ff39bc087a = $_ca64fdd45775e725bf94e9c8115c126e->getSuggestions($_0bbad16bbfba005ee653e95c0ccc3e6a); } else { return parent::noRouteAction(); } if ($_6858a7e083ad1067092d78ff39bc087a) { $this->getRequest()->setParam($_5461adfd1c55fd8f1c6c3dca5d516497->getQueryParamName(), $_6858a7e083ad1067092d78ff39bc087a); $this->_forward('noRouteLucene'); return true; } if ($_0bbad16bbfba005ee653e95c0ccc3e6a) { $this->getRequest()->setParam($_5461adfd1c55fd8f1c6c3dca5d516497->getQueryParamName(), $_0bbad16bbfba005ee653e95c0ccc3e6a); $this->_forward('noRouteLucene'); return true; } } public function noRouteLuceneAction() { $_5461adfd1c55fd8f1c6c3dca5d516497 = Mage::helper('catalogsearch'); $_6858a7e083ad1067092d78ff39bc087a = $_5461adfd1c55fd8f1c6c3dca5d516497->getQuery(); $_6858a7e083ad1067092d78ff39bc087a->setStoreId(Mage::app()->getStore()->getId()); if ($_6858a7e083ad1067092d78ff39bc087a->getQueryText()) { if (Mage::helper('catalogsearch')->isMinQueryLength()) { $_6858a7e083ad1067092d78ff39bc087a->setId(0) ->setIsActive(1) ->setIsProcessed(1); } else { if ($_6858a7e083ad1067092d78ff39bc087a->getId()) { $_6858a7e083ad1067092d78ff39bc087a->setPopularity($_6858a7e083ad1067092d78ff39bc087a->getPopularity()+1); } else { $_6858a7e083ad1067092d78ff39bc087a->setPopularity(1); } if ($_6858a7e083ad1067092d78ff39bc087a->getRedirect()) { $_6858a7e083ad1067092d78ff39bc087a->save(); $this->getResponse()->setRedirect($_6858a7e083ad1067092d78ff39bc087a->getRedirect()); return; } else { $_6858a7e083ad1067092d78ff39bc087a->prepare(); } } Mage::helper('catalogsearch')->checkNotes(); $this->getResponse()->setHttpResponseCode(404); $this->loadLayout(); $this->_initLayoutMessages('catalog/session'); $this->_initLayoutMessages('checkout/session'); $this->getLayout()->getBlock('head')->setTitle($this->__("404 alternative results")); $this->renderLayout(); if (!Mage::helper('catalogsearch')->isMinQueryLength()) { $_6858a7e083ad1067092d78ff39bc087a->save(); } } else { return parent::noRouteAction($_3b51e4b5dfe7c254ec475a5fa3befd56); } } public function lucenePresentAction() { $this->getResponse()->appendBody('OK'); } }
?>

