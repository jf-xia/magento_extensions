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
			 class Php4u_BlastLuceneSearch_Helper_Version extends Mage_Core_Helper_Abstract { public function isMageEnterprise() { return Mage::getConfig ()->getModuleConfig ( 'Enterprise_Enterprise' ) && Mage::getConfig ()->getModuleConfig ( 'Enterprise_AdminGws' ) && Mage::getConfig ()->getModuleConfig ( 'Enterprise_Checkout' ) && Mage::getConfig ()->getModuleConfig ( 'Enterprise_Customer' ); } public function isMageProfessional() { return Mage::getConfig ()->getModuleConfig ( 'Enterprise_Enterprise' ) && !Mage::getConfig ()->getModuleConfig ( 'Enterprise_AdminGws' ) && !Mage::getConfig ()->getModuleConfig ( 'Enterprise_Checkout' ) && !Mage::getConfig ()->getModuleConfig ( 'Enterprise_Customer' ); } public function isMageCommunity() { return !$this->isMageEnterprise() && !$this->isMageProfessional(); } public function convertVersionToCommunityVersion($_ed1b614ebb3eb197965b29b8d254eb6f = null, $_6bc9f2c1cccbc06b8f71b4bcb10bed10 = null) { if (is_null($_ed1b614ebb3eb197965b29b8d254eb6f)) { $_ed1b614ebb3eb197965b29b8d254eb6f = Mage::getVersion(); } if ($this->isMageEnterprise()) { if (version_compare ( $_ed1b614ebb3eb197965b29b8d254eb6f, '1.12.0', '>=' )) return '1.7.0'; if (version_compare ( $_ed1b614ebb3eb197965b29b8d254eb6f, '1.11.0', '>=' )) return '1.6.0'; if (version_compare ( $_ed1b614ebb3eb197965b29b8d254eb6f, '1.9.1', '>=' )) return '1.5.0'; if (version_compare ( $_ed1b614ebb3eb197965b29b8d254eb6f, '1.9.0', '>=' )) return '1.4.2'; if (version_compare ( $_ed1b614ebb3eb197965b29b8d254eb6f, '1.8.0', '>=' )) return '1.3.1'; return '1.3.1'; } if ($this->isMageProfessional()) { if (version_compare ( $_ed1b614ebb3eb197965b29b8d254eb6f, '1.8.0', '>=' )) return '1.4.1'; if (version_compare ( $_ed1b614ebb3eb197965b29b8d254eb6f, '1.7.0', '>=' )) return '1.3.1'; return '1.3.1'; } return $_ed1b614ebb3eb197965b29b8d254eb6f; } public function isBaseMageVersion($_ed1b614ebb3eb197965b29b8d254eb6f, $_6bc9f2c1cccbc06b8f71b4bcb10bed10 = null) { $_446b624f68b1af38caaae04d8d81d14d = $this->convertVersionToCommunityVersion ( Mage::getVersion (), $_6bc9f2c1cccbc06b8f71b4bcb10bed10 ); if (version_compare ( $_446b624f68b1af38caaae04d8d81d14d, $_ed1b614ebb3eb197965b29b8d254eb6f, '=' )) { return true; } return false; } }
?>

