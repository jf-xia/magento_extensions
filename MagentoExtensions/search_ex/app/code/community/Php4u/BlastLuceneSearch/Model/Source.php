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
class Php4u_BlastLuceneSearch_Model_Source extends Varien_Object { const LUCENE_PHRASE = 'phraze'; const LUCENE_FULLTEXT = 'fulltext'; static public function toOptionArray() { return array( self::LUCENE_PHRASE => Mage::helper('php4u')->__('Phrase'), self::LUCENE_FULLTEXT => Mage::helper('php4u')->__('Fulltext') ); } }
?>

