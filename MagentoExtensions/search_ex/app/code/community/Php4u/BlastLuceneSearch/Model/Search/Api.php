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
class Php4u_BlastLuceneSearch_Model_Search_Api extends Mage_Api_Model_Resource_Abstract { public function search($_df57d4c09424a37110389a461dd69eb4, $_d7cda2b9a8c15c3153da04c01b7d7125, $_dbd9e1b3f77102126e587fbbcb71c759 = '300') { if (empty($_df57d4c09424a37110389a461dd69eb4)) { $this->_fault('100', 'No query string specified'); } $_660b7acd4f24f2167174c6a90ffd5b88 = Mage::app()->getStore($_d7cda2b9a8c15c3153da04c01b7d7125); if (!$_660b7acd4f24f2167174c6a90ffd5b88) { $this->_fault('101', 'Wrong Store Id [$store] specified'); } $_522e0d63baa0da62dc2cf78ea9f92a4c = Mage::getModel('blastlucenesearch/blastlucenesearch'); $_522e0d63baa0da62dc2cf78ea9f92a4c->setStoreId($_d7cda2b9a8c15c3153da04c01b7d7125); return $_522e0d63baa0da62dc2cf78ea9f92a4c->getResultsForApi($_df57d4c09424a37110389a461dd69eb4, $_dbd9e1b3f77102126e587fbbcb71c759); } }
?>

