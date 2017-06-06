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
class license_application extends padl
{
    var $_ALLOWED_SERVER_DIFS = 0;
    var $_ALLOWED_IP_DIFS = 0;
    var $_LICENSE_PATH;
    function __construct($_e8c7ca863267e4812329a3fa231309ec = 'license.dat', $_80eff15fc1140ce5a35ef09a46a70b48 = true, $_8dc3da0a8a9af2fcc37197a872d356ac = true, $_7b952bc3b9eb4cb7308a3dfbde2ce004 = true, $_1b56c2d08c22bfd419be15345b99b646 = false)
    {
        $this->_check_secure();
        $this->_LICENSE_PATH = $_e8c7ca863267e4812329a3fa231309ec;
        $this->init($_80eff15fc1140ce5a35ef09a46a70b48, $_8dc3da0a8a9af2fcc37197a872d356ac, $_7b952bc3b9eb4cb7308a3dfbde2ce004, $_1b56c2d08c22bfd419be15345b99b646);
        if ($this->USE_SERVER) {
            $this->_MAC = $this->_get_mac_address();
        }
    }
    function set_server_vars($_1112b5ed43c7323d6b1e63fef0821d88)
    {
        $this->_check_secure();
        $this->_SERVER_VARS = $_1112b5ed43c7323d6b1e63fef0821d88;
        $this->_IPS         = $this->_get_ip_address();
        $this->_SERVER_INFO = $this->_get_server_info();
    }
    function _get_os_var($_e453603b9faf9241b94eeb317f5c3574, $_c8851e723cd8527db559893022dfddf7)
    {
        $_e453603b9faf9241b94eeb317f5c3574 = strtolower($_e453603b9faf9241b94eeb317f5c3574);
        switch ($_c8851e723cd8527db559893022dfddf7) {
            case 'freebsd':
            case 'netbsd':
            case 'solaris':
            case 'sunos':
            case 'darwin':
                switch ($_e453603b9faf9241b94eeb317f5c3574) {
                    case 'conf':
                        $_2be849eb7c9f52b1d42c66903aab0801 = '/sbin/ifconfig';
                        break;
                    case 'mac':
                        $_2be849eb7c9f52b1d42c66903aab0801 = 'ether';
                        break;
                    case 'ip':
                        $_2be849eb7c9f52b1d42c66903aab0801 = 'inet ';
                        break;
                }
                break;
            case 'linux':
                switch ($_e453603b9faf9241b94eeb317f5c3574) {
                    case 'conf':
                        $_2be849eb7c9f52b1d42c66903aab0801 = '/sbin/ifconfig';
                        break;
                    case 'mac':
                        $_2be849eb7c9f52b1d42c66903aab0801 = 'HWaddr';
                        break;
                    case 'ip':
                        $_2be849eb7c9f52b1d42c66903aab0801 = 'inet addr:';
                        break;
                }
                break;
        }
        return $_2be849eb7c9f52b1d42c66903aab0801;
    }
    function _get_config()
    {
        $this->_check_secure();
        if (ini_get('safe_mode')) {
            return 'SAFE_MODE';
        }
        $_c8851e723cd8527db559893022dfddf7 = strtolower(PHP_OS);
        if (substr($_c8851e723cd8527db559893022dfddf7, 0, 3) == 'win') {
            @exec('ipconfig/all', $_f6596996a3ec665a3810461b7ac30e56);
            if (count($_f6596996a3ec665a3810461b7ac30e56) == 0)
                return 'ERROR_OPEN';
            $_9a96279bc9307dcf901940e70c99b5e8 = implode($this->_LINEBREAK, $_f6596996a3ec665a3810461b7ac30e56);
        } else {
            $_9a8b7fe99921d7a2970fd836a5c9bc6b = $this->_get_os_var('conf', $_c8851e723cd8527db559893022dfddf7);
            $_05990fe5be014b5af8e49e7130780147 = @popen($_9a8b7fe99921d7a2970fd836a5c9bc6b, "rb");
            if (!$_05990fe5be014b5af8e49e7130780147)
                return 'ERROR_OPEN';
            $_9a96279bc9307dcf901940e70c99b5e8 = @fread($_05990fe5be014b5af8e49e7130780147, 4096);
            @pclose($_05990fe5be014b5af8e49e7130780147);
        }
        return $_9a96279bc9307dcf901940e70c99b5e8;
    }
    function _get_ip_address()
    {
        $_b93d696451c80ce40148fe0b8ee9a42c = array();
        $_9a96279bc9307dcf901940e70c99b5e8 = $this->_get_config();
        if ($_9a96279bc9307dcf901940e70c99b5e8 != 'SAFE_MODE' && $_9a96279bc9307dcf901940e70c99b5e8 != 'ERROR_OPEN') {
            $_c8851e723cd8527db559893022dfddf7 = strtolower(PHP_OS);
            if (substr($_c8851e723cd8527db559893022dfddf7, 0, 3) == 'win') {
            } else {
                $_f6596996a3ec665a3810461b7ac30e56 = explode($this->_LINEBREAK, $_9a96279bc9307dcf901940e70c99b5e8);
                $_434574e40ec0947bad92b053aea174f3 = $this->_get_os_var('ip', $_c8851e723cd8527db559893022dfddf7);
                $_f3dcbbb9863ce9c8522e22649ccaa3e3 = "(\\d|[1-9]\\d|1\\d\\d|2[0-4]\\d|25[0-5])";
                foreach ($_f6596996a3ec665a3810461b7ac30e56 as $_82ae283a175af44a463fa86c470a506d => $_f24806adb519efc5637fe7393fd19b31) {
                    if (!preg_match("/^$_f3dcbbb9863ce9c8522e22649ccaa3e3\\.$_f3dcbbb9863ce9c8522e22649ccaa3e3\\.$_f3dcbbb9863ce9c8522e22649ccaa3e3\\.$_f3dcbbb9863ce9c8522e22649ccaa3e3$/", $_f24806adb519efc5637fe7393fd19b31) && strpos($_f24806adb519efc5637fe7393fd19b31, $_434574e40ec0947bad92b053aea174f3)) {
                        $_58218960b263b2b6703c65bd5c8a2c1a = substr($_f24806adb519efc5637fe7393fd19b31, strpos($_f24806adb519efc5637fe7393fd19b31, $_434574e40ec0947bad92b053aea174f3) + strlen($_434574e40ec0947bad92b053aea174f3));
                        $_58218960b263b2b6703c65bd5c8a2c1a = trim(substr($_58218960b263b2b6703c65bd5c8a2c1a, 0, strpos($_58218960b263b2b6703c65bd5c8a2c1a, " ")));
                        if (!isset($_b93d696451c80ce40148fe0b8ee9a42c[$_58218960b263b2b6703c65bd5c8a2c1a]))
                            $_b93d696451c80ce40148fe0b8ee9a42c[$_58218960b263b2b6703c65bd5c8a2c1a] = $_58218960b263b2b6703c65bd5c8a2c1a;
                    }
                }
            }
        }
        if (isset($this->_SERVER_VARS['SERVER_NAME'])) {
            $_58218960b263b2b6703c65bd5c8a2c1a = gethostbyname($this->_SERVER_VARS['SERVER_NAME']);
            if (!isset($_b93d696451c80ce40148fe0b8ee9a42c[$_58218960b263b2b6703c65bd5c8a2c1a]))
                $_b93d696451c80ce40148fe0b8ee9a42c[$_58218960b263b2b6703c65bd5c8a2c1a] = $_58218960b263b2b6703c65bd5c8a2c1a;
        }
        if (isset($this->_SERVER_VARS['SERVER_ADDR'])) {
            $_98c82b488578ce0625cd8ca0198dcd91 = gethostbyaddr($this->_SERVER_VARS['SERVER_ADDR']);
            $_58218960b263b2b6703c65bd5c8a2c1a = gethostbyname($_98c82b488578ce0625cd8ca0198dcd91);
            if (!isset($_b93d696451c80ce40148fe0b8ee9a42c[$_58218960b263b2b6703c65bd5c8a2c1a]))
                $_b93d696451c80ce40148fe0b8ee9a42c[$_58218960b263b2b6703c65bd5c8a2c1a] = $_58218960b263b2b6703c65bd5c8a2c1a;
            if ($_58218960b263b2b6703c65bd5c8a2c1a != $this->_SERVER_VARS['SERVER_ADDR']) {
                if (!isset($_b93d696451c80ce40148fe0b8ee9a42c[$this->_SERVER_VARS['SERVER_ADDR']]))
                    $_b93d696451c80ce40148fe0b8ee9a42c[$this->_SERVER_VARS['SERVER_ADDR']] = $this->_SERVER_VARS['SERVER_ADDR'];
            }
        }
        if (count($_b93d696451c80ce40148fe0b8ee9a42c) > 0)
            return $_b93d696451c80ce40148fe0b8ee9a42c;
        if ($_9a96279bc9307dcf901940e70c99b5e8 == 'SAFE_MODE' || $_9a96279bc9307dcf901940e70c99b5e8 == 'ERROR_OPEN')
            return $_9a96279bc9307dcf901940e70c99b5e8;
        return 'IP_404';
    }
    function _get_mac_address()
    {
        $_9a96279bc9307dcf901940e70c99b5e8 = $this->_get_config();
        $_c8851e723cd8527db559893022dfddf7 = strtolower(PHP_OS);
        if (substr($_c8851e723cd8527db559893022dfddf7, 0, 3) == 'win') {
            $_f6596996a3ec665a3810461b7ac30e56 = explode($this->_LINEBREAK, $_9a96279bc9307dcf901940e70c99b5e8);
            foreach ($_f6596996a3ec665a3810461b7ac30e56 as $_82ae283a175af44a463fa86c470a506d => $_f24806adb519efc5637fe7393fd19b31) {
                if (preg_match("/([0-9a-f][0-9a-f][-:]){5}([0-9a-f][0-9a-f])/i", $_f24806adb519efc5637fe7393fd19b31)) {
                    $_d479ca0645ed1a0908a21e77c9d347ba = trim($_f24806adb519efc5637fe7393fd19b31);
                    return trim(substr($_d479ca0645ed1a0908a21e77c9d347ba, strrpos($_d479ca0645ed1a0908a21e77c9d347ba, " ")));
                }
            }
        } else {
            $_42608241497fd3026578bb7c8cc8d78e = $this->_get_os_var('mac', $_c8851e723cd8527db559893022dfddf7);
            $_e21caf61e91ca2bf81818911598da337 = strpos($_9a96279bc9307dcf901940e70c99b5e8, $_42608241497fd3026578bb7c8cc8d78e);
            if ($_e21caf61e91ca2bf81818911598da337) {
                $_d212a5d8e09d1fa21291f1f5b037781e = trim(substr($_9a96279bc9307dcf901940e70c99b5e8, ($_e21caf61e91ca2bf81818911598da337 + strlen($_42608241497fd3026578bb7c8cc8d78e))));
                return trim(substr($_d212a5d8e09d1fa21291f1f5b037781e, 0, strpos($_d212a5d8e09d1fa21291f1f5b037781e, "\n")));
            }
        }
        return 'MAC_404';
    }
    function _get_server_info()
    {
        if (empty($this->_SERVER_VARS)) {
            $this->set_server_vars($_SERVER);
        }
        $_b9548bb00b73affcf6445d120509d026 = array();
        if (isset($this->_SERVER_VARS['SERVER_ADDR']) && (!strrpos($this->_SERVER_VARS['SERVER_ADDR'], '127.0.0.1') || $this->ALLOW_LOCAL)) {
            $_b9548bb00b73affcf6445d120509d026['SERVER_ADDR'] = $this->_SERVER_VARS['SERVER_ADDR'];
        }
        if (isset($this->_SERVER_VARS['HTTP_HOST']) && (!strrpos($this->_SERVER_VARS['HTTP_HOST'], '127.0.0.1') || $this->ALLOW_LOCAL)) {
            $_b9548bb00b73affcf6445d120509d026['HTTP_HOST'] = $this->_SERVER_VARS['HTTP_HOST'];
        }
        if (isset($this->_SERVER_VARS['SERVER_NAME'])) {
            $_b9548bb00b73affcf6445d120509d026['SERVER_NAME'] = $this->_SERVER_VARS['SERVER_NAME'];
        }
        if (isset($this->_SERVER_VARS['PATH_TRANSLATED'])) {
            $_b9548bb00b73affcf6445d120509d026['PATH_TRANSLATED'] = substr($this->_SERVER_VARS['PATH_TRANSLATED'], 0, strrpos($this->_SERVER_VARS['PATH_TRANSLATED'], '/'));
        } else if (isset($this->_SERVER_VARS['SCRIPT_FILENAME'])) {
            $_b9548bb00b73affcf6445d120509d026['SCRIPT_FILENAME'] = substr($this->_SERVER_VARS['SCRIPT_FILENAME'], 0, strrpos($this->_SERVER_VARS['SCRIPT_FILENAME'], '/'));
        }
        if (isset($this->_SERVER_VARS['SCRIPT_URI'])) {
            $_b9548bb00b73affcf6445d120509d026['SCRIPT_URI'] = substr($this->_SERVER_VARS['SCRIPT_URI'], 0, strrpos($this->_SERVER_VARS['SCRIPT_URI'], '/'));
        }
        if (count($_b9548bb00b73affcf6445d120509d026) < $this->REQUIRED_URIS) {
            return 'SERVER_FAILED';
        }
        return $_b9548bb00b73affcf6445d120509d026;
    }
    function validate($_605ff53ce7cad70c31c5235c94196af9 = false, $_5f6b1f660d51d5a5f4bc7a5ccf7a246a = false, $_afee28711c8973a07c63e76189c82049 = "", $_ccf4167e2cd7f542df172163faedd553 = "", $_225d15eefac72a657993c4bfddfdecce = "80")
    {
        $this->_check_secure();
        $_d23635bfbc0fc804ee6bee642bfde5bf = (!$_605ff53ce7cad70c31c5235c94196af9) ? @file_get_contents($this->_LICENSE_PATH) : $_605ff53ce7cad70c31c5235c94196af9;
        if (strlen($_d23635bfbc0fc804ee6bee642bfde5bf) > 0) {
            $_911bcb28566bf59fec5e7a19446b6350 = $this->_unwrap_license($_d23635bfbc0fc804ee6bee642bfde5bf);
            if (is_array($_911bcb28566bf59fec5e7a19446b6350)) {
                if ($_911bcb28566bf59fec5e7a19446b6350['ID'] != md5($this->ID1)) {
                    $_911bcb28566bf59fec5e7a19446b6350['RESULT'] = 'CORRUPT';
                }
                if ($this->USE_TIME) {
                    if ($_911bcb28566bf59fec5e7a19446b6350['DATE']['START'] > time() + $this->START_DIF) {
                        $_911bcb28566bf59fec5e7a19446b6350['RESULT'] = 'TMINUS';
                    }
                    if ($_911bcb28566bf59fec5e7a19446b6350['DATE']['END'] - time() < 0 && $_911bcb28566bf59fec5e7a19446b6350['DATE']['SPAN'] != 'NEVER') {
                        $_911bcb28566bf59fec5e7a19446b6350['RESULT'] = 'EXPIRED';
                    }
                    $_911bcb28566bf59fec5e7a19446b6350['DATE']['HUMAN']['START'] = date($this->DATE_STRING, $_911bcb28566bf59fec5e7a19446b6350['DATE']['START']);
                    $_911bcb28566bf59fec5e7a19446b6350['DATE']['HUMAN']['END']   = date($this->DATE_STRING, $_911bcb28566bf59fec5e7a19446b6350['DATE']['END']);
                }
                if ($this->USE_SERVER) {
                    $_a36d6ad6592f194d52950b4646aaec00 = $this->_compare_domain($this->_SERVER_INFO['HTTP_HOST'], $_911bcb28566bf59fec5e7a19446b6350['SERVER']['DOMAIN']);
                    if (!$_a36d6ad6592f194d52950b4646aaec00) {
                        $_911bcb28566bf59fec5e7a19446b6350['RESULT'] = 'ILLEGAL';
                    }
                    $_941f17ed12ffc40279c251592bb11e5a = $this->ALLOW_LOCAL && (in_array('127.0.0.1', $_911bcb28566bf59fec5e7a19446b6350['SERVER']['IP']) || $_911bcb28566bf59fec5e7a19446b6350['PATH']['SERVER_ADDR'] == '127.0.0.1' || $_911bcb28566bf59fec5e7a19446b6350['PATH']['HTTP_HOST'] == '127.0.0.1');
                    if (!$_941f17ed12ffc40279c251592bb11e5a) {
                        $_911bcb28566bf59fec5e7a19446b6350['RESULT'] = 'ILLEGAL_LOCAL';
                    }
                }
                if (isset($_911bcb28566bf59fec5e7a19446b6350['DATA']['type'])) {
                    if ($_911bcb28566bf59fec5e7a19446b6350['DATA']['type'] == 'trial')
                        if (isset($_911bcb28566bf59fec5e7a19446b6350['RESULT']) && $_911bcb28566bf59fec5e7a19446b6350['RESULT'] == 'ILLEGAL') {
                            $_911bcb28566bf59fec5e7a19446b6350['RESULT'] = 'OK';
                        }
                }
                if (!isset($_911bcb28566bf59fec5e7a19446b6350['RESULT'])) {
                    if ($_5f6b1f660d51d5a5f4bc7a5ccf7a246a) {
                        $_ae1941058062fc5aba0792b4f86582c3                        = array();
                        $_ae1941058062fc5aba0792b4f86582c3['LICENSE_DATA']        = $_911bcb28566bf59fec5e7a19446b6350;
                        $_ae1941058062fc5aba0792b4f86582c3['LICENSE_DATA']['KEY'] = md5($_d23635bfbc0fc804ee6bee642bfde5bf);
                        $_911bcb28566bf59fec5e7a19446b6350['RESULT']              = $this->_call_home($_ae1941058062fc5aba0792b4f86582c3, $_afee28711c8973a07c63e76189c82049, $_ccf4167e2cd7f542df172163faedd553, $_225d15eefac72a657993c4bfddfdecce);
                    } else {
                        $_911bcb28566bf59fec5e7a19446b6350['RESULT'] = 'OK';
                    }
                }
                return $_911bcb28566bf59fec5e7a19446b6350;
            } else {
                return array(
                    'RESULT' => 'INVALID'
                );
            }
        }
        return array(
            'RESULT' => 'EMPTY'
        );
    }
    function _call_home($_04b5bffa7fc672ef5d359b538f37b0e0, $_afee28711c8973a07c63e76189c82049, $_ccf4167e2cd7f542df172163faedd553, $_225d15eefac72a657993c4bfddfdecce)
    {
        $_04b5bffa7fc672ef5d359b538f37b0e0 = $this->_post_data($_afee28711c8973a07c63e76189c82049, $_ccf4167e2cd7f542df172163faedd553, $_04b5bffa7fc672ef5d359b538f37b0e0, $_225d15eefac72a657993c4bfddfdecce);
        return (empty($_04b5bffa7fc672ef5d359b538f37b0e0['RESULT'])) ? 'SOCKET_FAILED' : $_04b5bffa7fc672ef5d359b538f37b0e0['RESULT'];
    }
}
?>

