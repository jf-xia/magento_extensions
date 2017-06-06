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
require_once("Mage/Adminhtml/controllers/System/CacheController.php"); 
class Php4u_Adminhtml_System_CacheController extends Mage_Adminhtml_System_CacheController { 
    public function saveAction() { 
        if ('rebuild_search_index_lucene' == $this->getRequest()->getPost('catalog_action')) { 
            try { 
                Mage::getSingleton('blastlucenesearch/lucene')->rebuildIndex();
                if (Mage::getStoreConfigFlag('php4u/php4u_group/php4u_lucene_onlynew',Mage::app()->getStore())) {
                    $_5f2cdc7ebe053a38de28e5bd3b9a8365 = Mage::helper('adminhtml')->__('Lucene Search Index Rebuilt successfully (ONLY NEW PRODUCTS - you can change it in settings)');
                } else { 
                    $_5f2cdc7ebe053a38de28e5bd3b9a8365 = Mage::helper('adminhtml')->__('Lucene Search Index Rebuilt successfully (ALL PRODUCTS - you can change it in settings)');
                } 
                $this->_getSession()->addSuccess($_5f2cdc7ebe053a38de28e5bd3b9a8365);
            } catch (Mage_Core_Exception $_56c289a57164f497b9579fe5fb9ed9fe) { 
                $this->_getSession()->addError($_56c289a57164f497b9579fe5fb9ed9fe->getMessage()); 
            } catch (Exception $_56c289a57164f497b9579fe5fb9ed9fe) { 
                die($_56c289a57164f497b9579fe5fb9ed9fe->getMessage().$_56c289a57164f497b9579fe5fb9ed9fe->getTraceAsString());
                $this->_getSession()->addException($_56c289a57164f497b9579fe5fb9ed9fe, Mage::helper('adminhtml')->__('Error while rebuilding Lucene Search Index. Please try again later'));
            } 
        } 
        parent::saveAction(); 
    } 
}
?>

