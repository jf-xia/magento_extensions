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
class Php4u_BlastLuceneSearch_Helper_Data extends Mage_Core_Helper_Data
{
    public function parseUrl()
    {
        $_97bc1d6f5881fbb22f00b7ef13558abb = Mage::app()->getRequest()->getRequestUri();
        $_7b3ba27eb0eb420e23ddb0b4baddafee = parse_url($_97bc1d6f5881fbb22f00b7ef13558abb);
        $_e824197f89bdfda36d6984752f9ca0ef = isset($_7b3ba27eb0eb420e23ddb0b4baddafee['path']) ? $_7b3ba27eb0eb420e23ddb0b4baddafee['path'] : '';
        $_efe2cc515bc3a2dbb9dd26d4a8ad7a8b = isset($_7b3ba27eb0eb420e23ddb0b4baddafee['query']) ? $_7b3ba27eb0eb420e23ddb0b4baddafee['query'] : '';
        $_48faf5829f176d28420993aa1f3eb948 = array();
        foreach (explode('/', $_e824197f89bdfda36d6984752f9ca0ef) as $_8fc3f26c0783fec4fefc9c32773199bb) {
            if (!in_array($_8fc3f26c0783fec4fefc9c32773199bb, array(
                'catalog',
                'category',
                'product',
                'view',
                'admin',
                'dashboard'
            ))) {
                if (!is_numeric($_8fc3f26c0783fec4fefc9c32773199bb)) {
                    $_48faf5829f176d28420993aa1f3eb948[] = $this->_cleanString($_8fc3f26c0783fec4fefc9c32773199bb);
                } else {
                    $_e7d96e71517b8dc0c55d51d0c5ec2b16 = Mage::getModel('catalog/product')->load(intval($_8fc3f26c0783fec4fefc9c32773199bb));
                    if ($_e7d96e71517b8dc0c55d51d0c5ec2b16->getId()) {
                        $_48faf5829f176d28420993aa1f3eb948[] = $_e7d96e71517b8dc0c55d51d0c5ec2b16->getSku();
                    } else {
                        $_5498dc0f7232a0eaccd579bd70edf8a0 = Mage::getModel('catalog/category')->load(intval($_8fc3f26c0783fec4fefc9c32773199bb)); {
                            $_48faf5829f176d28420993aa1f3eb948[] = $_5498dc0f7232a0eaccd579bd70edf8a0->getName();
                        }
                    }
                }
            }
        }
        $_61bda06738e8eab959c9731385fc5eef = array();
        $_0bd53adc568f0b58cceee3d3e6a084d2 = array();
        foreach (explode('&', $_efe2cc515bc3a2dbb9dd26d4a8ad7a8b) as $_201a1501ffe280abc9957d0674f65343) {
            if (strpos($_201a1501ffe280abc9957d0674f65343, '=') !== false) {
                $_61bda06738e8eab959c9731385fc5eef                                     = explode('=', $_201a1501ffe280abc9957d0674f65343);
                $_f6dba6f05b7ff18190ac6217ba508593                                     = $this->_cleanString($_61bda06738e8eab959c9731385fc5eef[0]);
                $_3140504565c1e12e62a0ff3787026ab0                                     = $this->_cleanString($_61bda06738e8eab959c9731385fc5eef[1]);
                $_0bd53adc568f0b58cceee3d3e6a084d2[$_f6dba6f05b7ff18190ac6217ba508593] = $_3140504565c1e12e62a0ff3787026ab0;
            }
        }
        $_7d3bf41c8e75c076905c7144752bd3c8 = implode(' ', $_48faf5829f176d28420993aa1f3eb948) . ' ' . implode(' ', $_0bd53adc568f0b58cceee3d3e6a084d2);
        Mage::dispatchEvent(Php4u_BlastLuceneSearch_Model_Event::EVENT_404_PARSER, array(
            'query' => $_7d3bf41c8e75c076905c7144752bd3c8
        ));
        return $_7d3bf41c8e75c076905c7144752bd3c8;
    }
    protected function _cleanString($_e8dd275af64b4f11e91ff4eeaceccc20)
    {
        $_e8dd275af64b4f11e91ff4eeaceccc20 = str_replace('_', ' ', $_e8dd275af64b4f11e91ff4eeaceccc20);
        $_e8dd275af64b4f11e91ff4eeaceccc20 = str_replace('-', ' ', $_e8dd275af64b4f11e91ff4eeaceccc20);
        $_e8dd275af64b4f11e91ff4eeaceccc20 = str_ireplace('.html', '', $_e8dd275af64b4f11e91ff4eeaceccc20);
        $_e8dd275af64b4f11e91ff4eeaceccc20 = str_ireplace('.htm', '', $_e8dd275af64b4f11e91ff4eeaceccc20);
        $_e8dd275af64b4f11e91ff4eeaceccc20 = str_ireplace('index.php', '', $_e8dd275af64b4f11e91ff4eeaceccc20);
        $_e8dd275af64b4f11e91ff4eeaceccc20 = str_ireplace('.php', '', $_e8dd275af64b4f11e91ff4eeaceccc20);
        $_e8dd275af64b4f11e91ff4eeaceccc20 = str_ireplace('.cgi', '', $_e8dd275af64b4f11e91ff4eeaceccc20);
        $_e8dd275af64b4f11e91ff4eeaceccc20 = str_replace('|', ' ', $_e8dd275af64b4f11e91ff4eeaceccc20);
        $_e8dd275af64b4f11e91ff4eeaceccc20 = Mage::helper('core/string')->cleanString($_e8dd275af64b4f11e91ff4eeaceccc20);
        return $_e8dd275af64b4f11e91ff4eeaceccc20;
    }
    public function getAdminStatsTable($_d074e3d5700fce8a12bc2f2f62dcf872)
    {
        $_130580f849de00f177381e4b43d65ac0 = '';
        $_130580f849de00f177381e4b43d65ac0 .= "<p>Main indexer folder <strong>$_d074e3d5700fce8a12bc2f2f62dcf872</strong> " . (is_dir($_d074e3d5700fce8a12bc2f2f62dcf872) ? 'OK: Directory exists' : 'Error: Directory not exists') . "</p>";
        if (!is_dir($_d074e3d5700fce8a12bc2f2f62dcf872)) {
            $_130580f849de00f177381e4b43d65ac0 .= "<p><i>Check " . Mage::getBaseDir('var') . " folder permissions and then Refresh Search Lucene index (System -> Cache Management) </i></p>";
        } else {
            $_130580f849de00f177381e4b43d65ac0 .= "<br/><h4>Directories present (Check number of products in Lucene index folder below):</h4><br/>";
            $_130580f849de00f177381e4b43d65ac0 .= '    <div class="grid">    <table cellspacing="0" class="data border">          <colgroup><col width="135">          <col width="120">          <col width="95">          <col>          <col width="1">          </colgroup><thead>              <tr class="headings">                  <th>Store</th>                  <th>Processed</th>                  <th>Indexed</th>                  <th>Folder</th>                  <th class="last">Action</th>              </tr>          </thead>          <tbody>';
            foreach (new DirectoryIterator($_d074e3d5700fce8a12bc2f2f62dcf872) as $_ca01b682971a82d5506f427b8abf4dfe) {
                if ($_ca01b682971a82d5506f427b8abf4dfe->isDot())
                    continue;
                if ($_ca01b682971a82d5506f427b8abf4dfe->isDir()) {
                    try {
                        if ($_ca01b682971a82d5506f427b8abf4dfe->getFilename() > 0 && strpos($_ca01b682971a82d5506f427b8abf4dfe->getPathname(), 'dym') === false) {
                            $_bbea5a98e30b6ae223adf7c829bd1902 = Mage::app()->getStore($_ca01b682971a82d5506f427b8abf4dfe->getFilename());
                            $_e59631c1dfbf8790cb06228b08255e53 = Zend_Search_Lucene::open($_ca01b682971a82d5506f427b8abf4dfe->getPathname());
                            $_1da31a06e36c3ada967cca9d72605f26 = Mage::getModel('blastlucenesearch/blastlucenesearch')->setStoreId($_ca01b682971a82d5506f427b8abf4dfe->getFilename())->countIndexed();
                            $_407a53fc27f3e5d030272e6be1ddafb2 = Mage::helper("adminhtml")->getUrl("blastlucenesearch/adminhtml_blastlucenesearch/mark/", array(
                                "storeId" => $_bbea5a98e30b6ae223adf7c829bd1902->getId()
                            ));
                            $_130580f849de00f177381e4b43d65ac0 .= "<tr>            <td><strong>" . $_bbea5a98e30b6ae223adf7c829bd1902->getName() . "</strong> (ID <strong>" . $_bbea5a98e30b6ae223adf7c829bd1902->getId() . "</strong>)</td>            <td class=\"a-right\"><div id=\"processed-store-" . $_bbea5a98e30b6ae223adf7c829bd1902->getId() . "\">" . $_1da31a06e36c3ada967cca9d72605f26 . "</div></td>            <td class=\"a-right\"><div id=\"indexed-store-" . $_bbea5a98e30b6ae223adf7c829bd1902->getId() . "\">" . $_e59631c1dfbf8790cb06228b08255e53->numDocs() . "</div></td>            <td class=\"nobr\"><small class=\"nobr\">" . $_ca01b682971a82d5506f427b8abf4dfe->getPathname() . "</small></td>            <td class=\"last\">             <button onclick=\"window.location='{$_407a53fc27f3e5d030272e6be1ddafb2}';return false;\" id=\"\" class=\"scalable delete icon-btn\" type=\"button\" title=\"Mark all as require indexing\"><span>Mark all as require indexing</span></button>            </td>           </tr>";
                        }
                    }
                    catch (Exception $_debe434b5965361893293b281774d663) {
                        $_130580f849de00f177381e4b43d65ac0 .= "<tr><td colspan=\"5\"><li>Error: " . $_ca01b682971a82d5506f427b8abf4dfe->getPathname() . " is not valid lucene index format? Message: " . $_debe434b5965361893293b281774d663->getMessage() . "</td></tr>";
                    }
                }
            }
            $_130580f849de00f177381e4b43d65ac0 .= '</tbody>          <tfoot>              <tr>                  <td class="" colspan="5">                  If \'Processed\' number is lower than number of enabled, in stock and searchable products in your store then you MUST reindex<br/>         This number can be higher than number of products in index if you have configurable/grouped/bundle products in your store which are not visible separately.<br/>                  </td>              </tr>          </tfoot>      </table></div>';
            return $_130580f849de00f177381e4b43d65ac0;
        }
    }
}
?>

