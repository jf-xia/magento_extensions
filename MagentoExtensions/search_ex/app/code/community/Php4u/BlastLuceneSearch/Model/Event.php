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
class Php4u_BlastLuceneSearch_Model_Event extends Mage_Core_Model_Abstract { const SEARCH_DONE = 'blastsearchlucene_search_done'; const SEARCH_MODE_CACHE = 'cache'; const SEARCH_MODE_NORMAL = 'normal'; const SEARCH_MODE_FUZZY_WILDCARD = 'fuzzy_wildcard'; const EVENT_404_PARSER = 'blastsearchlucene_search_404_parser'; const EVENT_PROCESS_HITS = 'blastsearchlucene_process_hits'; public final function throwSearchDone($_02c98eae8e4e6058d97256373ea293cd = '') { if ($_02c98eae8e4e6058d97256373ea293cd != self::SEARCH_MODE_NORMAL && $_02c98eae8e4e6058d97256373ea293cd != self::SEARCH_MODE_FUZZY_WILDCARD && $_02c98eae8e4e6058d97256373ea293cd != self::SEARCH_MODE_CACHE) { return; } Mage::dispatchEvent(self::SEARCH_DONE, array('method'=>$_02c98eae8e4e6058d97256373ea293cd)); } }
?>

