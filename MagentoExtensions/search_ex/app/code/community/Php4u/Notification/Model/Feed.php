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
class Php4u_Notification_Model_Feed extends Mage_AdminNotification_Model_Feed { const XML_FEED_URL_PATH = 'php4u/adminnotification/feed_url'; const XML_FREQUENCY_PATH = 'php4u/adminnotification/frequency'; const XML_LAST_UPDATE_PATH = 'php4u/adminnotification/last_update'; protected $_feedUrl; protected function _construct() {} public function getFeedUrl() { if (is_null($this->_feedUrl)) { $this->_feedUrl = 'http://' . Mage::getStoreConfig(self::XML_FEED_URL_PATH); } return $this->_feedUrl; } public function checkUpdate() { if (($this->getFrequency() + $this->getLastUpdate()) > time()) { return $this; } $_a7363bc9751f8ed532edafff1af1d2df = array(); $_1e2ee7ca219d35d2dd8dc92cb6f7f0ef = $this->getFeedData(); if ($_1e2ee7ca219d35d2dd8dc92cb6f7f0ef && $_1e2ee7ca219d35d2dd8dc92cb6f7f0ef->channel && $_1e2ee7ca219d35d2dd8dc92cb6f7f0ef->channel->item) { foreach ($_1e2ee7ca219d35d2dd8dc92cb6f7f0ef->channel->item as $_0fae2a85a65f8edec60512db40c3e0ef) { $_a7363bc9751f8ed532edafff1af1d2df[] = array( 'severity' => (int)$_0fae2a85a65f8edec60512db40c3e0ef->severity, 'date_added' => $this->getDate((string)$_0fae2a85a65f8edec60512db40c3e0ef->pubDate), 'title' => (string)$_0fae2a85a65f8edec60512db40c3e0ef->title, 'description' => (string)$_0fae2a85a65f8edec60512db40c3e0ef->description, 'url' => (string)$_0fae2a85a65f8edec60512db40c3e0ef->link, ); } if ($_a7363bc9751f8ed532edafff1af1d2df) { Mage::getModel('adminnotification/inbox')->parse(array_reverse($_a7363bc9751f8ed532edafff1af1d2df)); } } $this->setLastUpdate(); return $this; } public function getDate($_b8ebf92979188f2daf40ce72a6c91b24) { return gmdate('Y-m-d H:i:s', strtotime($_b8ebf92979188f2daf40ce72a6c91b24)); } public function getFrequency() { return Mage::getStoreConfig(self::XML_FREQUENCY_PATH) * 3600; } public function getLastUpdate() { return Mage::app()->loadCache('php4u_notifications_lastcheck'); } public function setLastUpdate() { Mage::app()->saveCache(time(), 'php4u_notifications_lastcheck'); return $this; } public function getFeedData() { $_603d4e6271f03c405b911b3856a97064 = new Varien_Http_Adapter_Curl(); $_603d4e6271f03c405b911b3856a97064->setConfig(array( 'timeout' => 2 )); $_603d4e6271f03c405b911b3856a97064->write(Zend_Http_Client::GET, $this->getFeedUrl(), '1.0'); $_e69ff613168f8525bffd3a3ddceb18b7 = $_603d4e6271f03c405b911b3856a97064->read(); if ($_e69ff613168f8525bffd3a3ddceb18b7 === false) { return false; } $_e69ff613168f8525bffd3a3ddceb18b7 = preg_split('/^\r?$/m', $_e69ff613168f8525bffd3a3ddceb18b7, 2); $_e69ff613168f8525bffd3a3ddceb18b7 = trim($_e69ff613168f8525bffd3a3ddceb18b7[1]); $_603d4e6271f03c405b911b3856a97064->close(); try { $_100080d3ee9c814d91706a331aeb6abc = new SimpleXMLElement($_e69ff613168f8525bffd3a3ddceb18b7); } catch (Exception $_22276809b45e2bdcd91475016a279fbe) { return false; } return $_100080d3ee9c814d91706a331aeb6abc; } public function getFeedXml() { try { $_e69ff613168f8525bffd3a3ddceb18b7 = $this->getFeedData(); $_100080d3ee9c814d91706a331aeb6abc = new SimpleXMLElement($_e69ff613168f8525bffd3a3ddceb18b7); } catch (Exception $_22276809b45e2bdcd91475016a279fbe) { $_100080d3ee9c814d91706a331aeb6abc = new SimpleXMLElement('xml version="1.0" encoding="utf-8" '); } return $_100080d3ee9c814d91706a331aeb6abc; } }
?>

