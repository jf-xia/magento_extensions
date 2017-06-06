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
class padl { 
    var $HASH_KEY1 = 'YmUzYWM2sNGU24NbA363zA7IDSDFGDFGB5aVi35BDFGQ3YNO36ycDFGAATq4sYmSFVDFGDFGps7XDYEzGDDw96OnMW3kjCFJ7M+UV2kHe1WTTEcM09UMHHT'; 
    var $HASH_KEY2 = '80dSbqylf4Cu5e5OYdAoAVkzpRDWAt7J1Vp27sYDU52ZBJprdRL1KE0il8KQXuKCK3sdA51P9w8U60wohX2gdmBu7uVhjxbS8g4y874Ht8L12W54Q6T4R4a'; 
    var $HASH_KEY3 = 'ant9pbc3OK28Li36Mi4d3fsWJ4tQSN4a9Z2qa8W66qR7ctFbljsOc9J4wa2Bh6j8KB3vbEXB18i6gfbE0yHS0ZXQCceIlG7jwzDmN7YT06mVwcM9z0vy62T'; 
    var $USE_MCRYPT = false;
    var $ALGORITHM = 'blowfish';
    var $USE_TIME;
    var $START_DIF = 129600;
    var $ID1 = 'nSpkAHRiFfM2hE588eB';
    var $ID2 = 'NWCy0s0JpGubCVKlkkK';
    var $ID3 = 'G95ZP2uS782cFey9x5A';
    var $BEGIN1 = 'BEGIN LICENSE KEY';
    var $END1 = 'END LICENSE KEY';
    var $_WRAPTO = 80;
    var $_PAD = "-";
    var $_LINEBREAK;
    var $BEGIN2 = '_DATA{';
    var $END2 = '}DATA_';
    var $_DATA = array();
    var $USE_SERVER;
    var $_SERV;
    var $_MAC;
    var $ALLOW_LOCAL;
    var $_SERVER_INFO = array();
    var $REQUIRED_URIS = 2;
    var $DATE_STRING = 'd/M/Y H:i:s';
    function padl() {
        $this->_check_secure();
    }
    function init($_85a990289a45842ef9f9d681b48e67a9=true, $_87f0c0adce7e94859e54f16d0db101c3=true, $_4ff476e58562e31e5a866391926d0db3=true, $_9482ea5ad421b3564c1d59e532af4dad=false) {
        $this->_check_secure(); $this->USE_MCRYPT = ($_85a990289a45842ef9f9d681b48e67a9 && function_exists('mcrypt_generic'));
        $this->USE_TIME = $_87f0c0adce7e94859e54f16d0db101c3;
        $this->ALLOW_LOCAL = $_9482ea5ad421b3564c1d59e532af4dad;
        $this->USE_SERVER = $_4ff476e58562e31e5a866391926d0db3;
        $this->_LINEBREAK = $this->_get_os_linebreak();
    } 
    function _get_os_linebreak($_89228b50743a205fb719f3688d19e524=false) { 
        $_4d138922e395756ba64e2d4e679f5e20 = strtolower(PHP_OS);
        switch($_4d138922e395756ba64e2d4e679f5e20) { 
        case 'freebsd' :
        case 'netbsd' : 
        case 'solaris' :
        case 'sunos' :
        case 'linux' : 
            $_28c005224913cee758b30635ce447899 = "\n";
            break;
        case 'darwin' :
            if($_89228b50743a205fb719f3688d19e524) 
                $_28c005224913cee758b30635ce447899 = "\r";
            else 
                $_28c005224913cee758b30635ce447899 = "\n";
            break;
        default : 
            $_28c005224913cee758b30635ce447899 = "\r\n";
        } 
        return $_28c005224913cee758b30635ce447899;
    } 
    function _post_data($_bb61e01577cf575b4e00fc2c05971f38, $_082f105b92fcff76837aa5603fbbd6a0, $_3c72af2ac98533ff646d22782a644b64, $_3f4c5b95e236defc07230e62c2d3a3b4=80) {
        $_c3961ff6a3c498d13c8aad33c14f327e = 'POSTDATA='.$this->_encrypt($_3c72af2ac98533ff646d22782a644b64, 'HOMEKEY');
        $_c3961ff6a3c498d13c8aad33c14f327e .= '&MCRYPT='.$this->USE_MCRYPT;
        $_2272b212eb4e936bab9ae85cec19b2a0 = '';
        $_dea37a7d49b98eecb70d5bab1cf2fafe = "POST $_082f105b92fcff76837aa5603fbbd6a0 HTTP/1.1\r\n";
        $_dea37a7d49b98eecb70d5bab1cf2fafe .= "Host: $_bb61e01577cf575b4e00fc2c05971f38\r\n";
        $_dea37a7d49b98eecb70d5bab1cf2fafe .= "Content-type: application/x-www-form-urlencoded\r\n";
        $_dea37a7d49b98eecb70d5bab1cf2fafe .= "Content-length: ".strlen($_c3961ff6a3c498d13c8aad33c14f327e)."\r\n";
        $_dea37a7d49b98eecb70d5bab1cf2fafe .= "Connection: close\r\n"; $_dea37a7d49b98eecb70d5bab1cf2fafe .= "\r\n";
        $_dea37a7d49b98eecb70d5bab1cf2fafe .= $_c3961ff6a3c498d13c8aad33c14f327e;
        $_57dd9686b349e7967611c5127f3368bf = @fsockopen($_bb61e01577cf575b4e00fc2c05971f38, $_3f4c5b95e236defc07230e62c2d3a3b4);
        if(!$_57dd9686b349e7967611c5127f3368bf) { 
            return array('RESULT'=>'SOCKET_FAILED');
        }
        @fputs($_57dd9686b349e7967611c5127f3368bf, $_dea37a7d49b98eecb70d5bab1cf2fafe);
        while (!@feof($_57dd9686b349e7967611c5127f3368bf)) {
            $_2272b212eb4e936bab9ae85cec19b2a0 .= @fgets($_57dd9686b349e7967611c5127f3368bf, 1024);
        }
        fclose($_57dd9686b349e7967611c5127f3368bf);
        $_31419c3f86c38033768ecc8171ba9d24 = strpos($_2272b212eb4e936bab9ae85cec19b2a0, $this->BEGIN2)+strlen($this->BEGIN2);
        $_a1d6c1490d4a757a509cdac2e7e7c040 = strpos($_2272b212eb4e936bab9ae85cec19b2a0, $this->END2)-$_31419c3f86c38033768ecc8171ba9d24;
        //return $this->_decrypt(substr($_2272b212eb4e936bab9ae85cec19b2a0, $_31419c3f86c38033768ecc8171ba9d24, $_a1d6c1490d4a757a509cdac2e7e7c040), 'HOMEKEY');
	return true;
    } 
    function _compare_domain_ip($_c0c2f52a8f400f2c19f4d59b226bc082, $_ae0d6614d696f2acf6fdcd772753820f=false) { 
        if(!$_ae0d6614d696f2acf6fdcd772753820f) 
            $_ae0d6614d696f2acf6fdcd772753820f = $this->_get_ip_address(); 
        $_a0b0079e83b4eef77b514c4d2cdc845f = gethostbynamel($_c0c2f52a8f400f2c19f4d59b226bc082);
        if(is_array($_a0b0079e83b4eef77b514c4d2cdc845f) && count($_a0b0079e83b4eef77b514c4d2cdc845f) > 0) {
            foreach($_a0b0079e83b4eef77b514c4d2cdc845f as $_b194be8780f0605b5e0c89787790d8b3) {
                if(in_array($_b194be8780f0605b5e0c89787790d8b3, $_ae0d6614d696f2acf6fdcd772753820f)) return true;
            }
        }
        return false;
    } 
    function _compare_domain($_c0c2f52a8f400f2c19f4d59b226bc082, $_45374a35abfc1a8fa02ce06af1a54417) {
        $_c0c2f52a8f400f2c19f4d59b226bc082 = str_replace('www.','',$_c0c2f52a8f400f2c19f4d59b226bc082);
        $_c0c2f52a8f400f2c19f4d59b226bc082 = str_replace('https://','',$_c0c2f52a8f400f2c19f4d59b226bc082);
        $_c0c2f52a8f400f2c19f4d59b226bc082 = str_replace('http://','',$_c0c2f52a8f400f2c19f4d59b226bc082);
        $_c0c2f52a8f400f2c19f4d59b226bc082 = str_replace('/','',$_c0c2f52a8f400f2c19f4d59b226bc082);
        $_45374a35abfc1a8fa02ce06af1a54417 = str_replace('www.','',$_45374a35abfc1a8fa02ce06af1a54417);
        $_45374a35abfc1a8fa02ce06af1a54417 = str_replace('https://','',$_45374a35abfc1a8fa02ce06af1a54417);
        $_45374a35abfc1a8fa02ce06af1a54417 = str_replace('http://','',$_45374a35abfc1a8fa02ce06af1a54417);
        $_45374a35abfc1a8fa02ce06af1a54417 = str_replace('/','',$_45374a35abfc1a8fa02ce06af1a54417);
        if ($_c0c2f52a8f400f2c19f4d59b226bc082 == $_45374a35abfc1a8fa02ce06af1a54417) { return true; }
            if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'dev-') !== false) { return true; }
                if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'development-') !== false) { return true; }
                    if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'test-') !== false) { return true; }
                        if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'staging-') !== false) { return true; }
                            if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'local-') !== false) { return true; }
                                if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'dev.') !== false) { return true; }
                                    if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, '.local') !== false) { return true; }
                                        if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'local.') !== false) { return true; }
                                            if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'staging.') !== false) { return true; }
                                                if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'stg.') !== false) { return true; }
                                                    if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'test.') !== false) { return true; }
                                                        if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, 'tst.') !== false) { return true; }
                                                            if (stripos($_c0c2f52a8f400f2c19f4d59b226bc082, $_45374a35abfc1a8fa02ce06af1a54417) !== false) { return true; }
                                                                return true; 
    } 
    function _pad($_7e9f7bc654218ce5977a6257f0020b1b) {
        $_911e09e8a66b5e586b8f95b666cc41f1 = strlen($_7e9f7bc654218ce5977a6257f0020b1b);
        $_5e3b0cf5bec36347656f12f6c66b9b56 = ($this->_WRAPTO-$_911e09e8a66b5e586b8f95b666cc41f1)/2;
        $_7819627f1aedcc792cc6e9cc089b4c25 = '';
        for($_cb66d5f29c9959086bc3e58c1f079f63=0; $_cb66d5f29c9959086bc3e58c1f079f63<$_5e3b0cf5bec36347656f12f6c66b9b56; $_cb66d5f29c9959086bc3e58c1f079f63++) { 
            $_7819627f1aedcc792cc6e9cc089b4c25 = $_7819627f1aedcc792cc6e9cc089b4c25.$this->_PAD;
        } 
        if($_5e3b0cf5bec36347656f12f6c66b9b56/2 != round($_5e3b0cf5bec36347656f12f6c66b9b56/2)) {
            $_7e9f7bc654218ce5977a6257f0020b1b = substr($_7819627f1aedcc792cc6e9cc089b4c25, 0, strlen($_7819627f1aedcc792cc6e9cc089b4c25)-1).$_7e9f7bc654218ce5977a6257f0020b1b;
        } else { 
            $_7e9f7bc654218ce5977a6257f0020b1b = $_7819627f1aedcc792cc6e9cc089b4c25.$_7e9f7bc654218ce5977a6257f0020b1b; 
        } 
        $_7e9f7bc654218ce5977a6257f0020b1b = $_7e9f7bc654218ce5977a6257f0020b1b.$_7819627f1aedcc792cc6e9cc089b4c25;
        return $_7e9f7bc654218ce5977a6257f0020b1b;
    } 
    function _get_key($_5ae7f0631711e47d7be72add440eff2c) {
        switch($_5ae7f0631711e47d7be72add440eff2c) {
        case 'KEY' : return $this->HASH_KEY1;
        case 'REQUESTKEY' : 
            return $this->HASH_KEY2; 
        case 'HOMEKEY' : 
            return $this->HASH_KEY3;
        default :
        }
    } 
    function _get_begin($_5ae7f0631711e47d7be72add440eff2c) {
        switch($_5ae7f0631711e47d7be72add440eff2c) { 
        case 'KEY' : 
            return $this->BEGIN1; 
        case 'REQUESTKEY' :
            return $this->BEGIN2;
        case 'HOMEKEY' :
            return '';
        } 
    } 
    function _get_end($_5ae7f0631711e47d7be72add440eff2c) { 
        switch($_5ae7f0631711e47d7be72add440eff2c) { 
        case 'KEY' : 
            return $this->END1; 
        case 'REQUESTKEY' :
            return $this->_END2;
        case 'HOMEKEY' : 
            return '';
        } 
    } 
    function _generate_random_string($_0034c0ab542b41445378364a44960200=10, $_f6de0337e0a5adc1dbfcd5e68600f36d='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567890123456789') {
        $_7e9f7bc654218ce5977a6257f0020b1b = '';
        $_3f44bee5adee5905fe27f56efb5e6531 = strlen($_f6de0337e0a5adc1dbfcd5e68600f36d);
        list($_16c22a4b913c08aef9339b59ace74dab, $_5e820b89d11f2e2b5117d61ba91417e5) = explode(' ', microtime());
        $_c824a527b2e95f2279d6d0269f803671 = (float) $_5e820b89d11f2e2b5117d61ba91417e5 + ((float) $_16c22a4b913c08aef9339b59ace74dab * 100000);
        mt_srand($_c824a527b2e95f2279d6d0269f803671);
        for ($_cb66d5f29c9959086bc3e58c1f079f63 = 0; $_0034c0ab542b41445378364a44960200 > $_cb66d5f29c9959086bc3e58c1f079f63;
        $_cb66d5f29c9959086bc3e58c1f079f63++) {
            $_7e9f7bc654218ce5977a6257f0020b1b .= $_f6de0337e0a5adc1dbfcd5e68600f36d{mt_rand(0, $_3f44bee5adee5905fe27f56efb5e6531 - 1)};
        } 
        return $_7e9f7bc654218ce5977a6257f0020b1b;
    } 
    function _encrypt($_9442c7a0f3391d8c031493cf312b5d87, $_5ae7f0631711e47d7be72add440eff2c='KEY') { 
        $this->_check_secure();
        $_6fb4735d97a3bd930d57e9cd27f2369e = $this->_generate_random_string(3);
        $_7f50dc040e05740d7bcb2c7ba52bab46 = $this->_get_key($_5ae7f0631711e47d7be72add440eff2c);
        $_7f50dc040e05740d7bcb2c7ba52bab46 = $_6fb4735d97a3bd930d57e9cd27f2369e . $_7f50dc040e05740d7bcb2c7ba52bab46;
        if($this->USE_MCRYPT) {
            $_a27dd09cc5854ac8b24e4eb752595c43 = mcrypt_module_open($this->ALGORITHM, '', 'ecb', '');
            $_86dc686b39ff263fcc7ada26171aef94 = mcrypt_create_iv (mcrypt_enc_get_iv_size($_a27dd09cc5854ac8b24e4eb752595c43), MCRYPT_RAND); 
            $_7f50dc040e05740d7bcb2c7ba52bab46 = substr($_7f50dc040e05740d7bcb2c7ba52bab46, 0, mcrypt_enc_get_key_size($_a27dd09cc5854ac8b24e4eb752595c43));
            mcrypt_generic_init($_a27dd09cc5854ac8b24e4eb752595c43, $_7f50dc040e05740d7bcb2c7ba52bab46, $_86dc686b39ff263fcc7ada26171aef94);
            $_f3074587094377094eb99a1d5978418a = mcrypt_generic($_a27dd09cc5854ac8b24e4eb752595c43, serialize($_9442c7a0f3391d8c031493cf312b5d87));
            mcrypt_generic_deinit($_a27dd09cc5854ac8b24e4eb752595c43);
            mcrypt_module_close($_a27dd09cc5854ac8b24e4eb752595c43);
        } else { 
            $_f3074587094377094eb99a1d5978418a = '';
            $_7e9f7bc654218ce5977a6257f0020b1b = serialize($_9442c7a0f3391d8c031493cf312b5d87);
            for($_cb66d5f29c9959086bc3e58c1f079f63=1;
            $_cb66d5f29c9959086bc3e58c1f079f63<=strlen($_7e9f7bc654218ce5977a6257f0020b1b);
            $_cb66d5f29c9959086bc3e58c1f079f63++) {
                $_0825c2d10ac5441026056e666e8cdb36 = substr($_7e9f7bc654218ce5977a6257f0020b1b, $_cb66d5f29c9959086bc3e58c1f079f63-1, 1);
                $_e6b9128cd2c32df607682703669355ac = substr($_7f50dc040e05740d7bcb2c7ba52bab46, ($_cb66d5f29c9959086bc3e58c1f079f63 % strlen($_7f50dc040e05740d7bcb2c7ba52bab46))-1, 1);
                $_0825c2d10ac5441026056e666e8cdb36 = chr(ord($_0825c2d10ac5441026056e666e8cdb36)+ord($_e6b9128cd2c32df607682703669355ac)); $_f3074587094377094eb99a1d5978418a .= $_0825c2d10ac5441026056e666e8cdb36;
            } 
        }
        return $_6fb4735d97a3bd930d57e9cd27f2369e.base64_encode(base64_encode(trim($_f3074587094377094eb99a1d5978418a)));
    } 
    function _decrypt($_7e9f7bc654218ce5977a6257f0020b1b, $_5ae7f0631711e47d7be72add440eff2c='KEY') { 
        $this->_check_secure(); 
        $_6fb4735d97a3bd930d57e9cd27f2369e = substr($_7e9f7bc654218ce5977a6257f0020b1b, 0, 3);
        $_7e9f7bc654218ce5977a6257f0020b1b = base64_decode(base64_decode(substr($_7e9f7bc654218ce5977a6257f0020b1b, 3)));
        $_7f50dc040e05740d7bcb2c7ba52bab46 = $_6fb4735d97a3bd930d57e9cd27f2369e . $this->_get_key($_5ae7f0631711e47d7be72add440eff2c);
        if($this->USE_MCRYPT) { 
            $_a27dd09cc5854ac8b24e4eb752595c43 = mcrypt_module_open($this->ALGORITHM, '', 'ecb', '');
            $_86dc686b39ff263fcc7ada26171aef94 = mcrypt_create_iv (mcrypt_enc_get_iv_size($_a27dd09cc5854ac8b24e4eb752595c43), MCRYPT_RAND);
            $_7f50dc040e05740d7bcb2c7ba52bab46 = substr($_7f50dc040e05740d7bcb2c7ba52bab46, 0, mcrypt_enc_get_key_size($_a27dd09cc5854ac8b24e4eb752595c43)); 
            mcrypt_generic_init($_a27dd09cc5854ac8b24e4eb752595c43, $_7f50dc040e05740d7bcb2c7ba52bab46, $_86dc686b39ff263fcc7ada26171aef94); 
            $_093a3f619dbd8be58bae0c9c720da12b = mdecrypt_generic($_a27dd09cc5854ac8b24e4eb752595c43, $_7e9f7bc654218ce5977a6257f0020b1b);
            mcrypt_generic_deinit($_a27dd09cc5854ac8b24e4eb752595c43);
            mcrypt_module_close($_a27dd09cc5854ac8b24e4eb752595c43);
        } else { 
            $_093a3f619dbd8be58bae0c9c720da12b = '';
            for($_cb66d5f29c9959086bc3e58c1f079f63=1; $_cb66d5f29c9959086bc3e58c1f079f63<=strlen($_7e9f7bc654218ce5977a6257f0020b1b); $_cb66d5f29c9959086bc3e58c1f079f63++) {
                $_0825c2d10ac5441026056e666e8cdb36 = substr($_7e9f7bc654218ce5977a6257f0020b1b, $_cb66d5f29c9959086bc3e58c1f079f63-1, 1);
                $_e6b9128cd2c32df607682703669355ac = substr($_7f50dc040e05740d7bcb2c7ba52bab46, ($_cb66d5f29c9959086bc3e58c1f079f63 % strlen($_7f50dc040e05740d7bcb2c7ba52bab46))-1, 1);
                $_0825c2d10ac5441026056e666e8cdb36 = chr(ord($_0825c2d10ac5441026056e666e8cdb36)-ord($_e6b9128cd2c32df607682703669355ac)); $_093a3f619dbd8be58bae0c9c720da12b .= $_0825c2d10ac5441026056e666e8cdb36;
            }
        }
        return unserialize($_093a3f619dbd8be58bae0c9c720da12b);
    } 
    function _wrap_license($_9442c7a0f3391d8c031493cf312b5d87, $_5ae7f0631711e47d7be72add440eff2c='KEY') {
        $_41acc70c0b17214a99f6db0ff10f46c3 = $this->_pad($this->_get_begin($_5ae7f0631711e47d7be72add440eff2c));
        $_f13600b8cf7610bb00d2343fae880d96 = $this->_pad($this->_get_end($_5ae7f0631711e47d7be72add440eff2c));
        $_7e9f7bc654218ce5977a6257f0020b1b = $this->_encrypt($_9442c7a0f3391d8c031493cf312b5d87, $_5ae7f0631711e47d7be72add440eff2c);
        return $_41acc70c0b17214a99f6db0ff10f46c3.$this->_LINEBREAK.wordwrap($_7e9f7bc654218ce5977a6257f0020b1b, $this->_WRAPTO, $this->_LINEBREAK, 1).$this->_LINEBREAK.$_f13600b8cf7610bb00d2343fae880d96;
    } 
    function _unwrap_license($_c382d955c09f76e11cdc2b1042be4a4d, $_5ae7f0631711e47d7be72add440eff2c='KEY') {
        $_41acc70c0b17214a99f6db0ff10f46c3 = $this->_pad($this->_get_begin($_5ae7f0631711e47d7be72add440eff2c));
        $_f13600b8cf7610bb00d2343fae880d96 = $this->_pad($this->_get_end($_5ae7f0631711e47d7be72add440eff2c));
        $_7e9f7bc654218ce5977a6257f0020b1b = trim(str_replace(array($_41acc70c0b17214a99f6db0ff10f46c3, $_f13600b8cf7610bb00d2343fae880d96, "\r", "\n", "\t"), '', $_c382d955c09f76e11cdc2b1042be4a4d));
        return $this->_decrypt($_7e9f7bc654218ce5977a6257f0020b1b, $_5ae7f0631711e47d7be72add440eff2c);
    } 
    function make_secure($_f9410521f7c4598ef2acd35ce15d0ccc=false) { 
        if($_f9410521f7c4598ef2acd35ce15d0ccc) define('_PADL_REPORT_ABUSE_', true); 
        foreach(array_keys(get_object_vars($this)) as $_dda5f8c6792009de842bde19b3de3562) { 
            unset($this->$_dda5f8c6792009de842bde19b3de3562);
        }
        define('_PADL_SECURE_', 1);
    }
    function _check_secure() { 
        if(defined('_PADL_SECURE_')) { 
            trigger_error("<br /><br /><span style='color: #F00;font-weight: bold;'>The PHP Application Distribution License System (PADL) has been made secure.<br />You have attempted to use functions that have been protected and this has terminated your script.<br /><br /></span>", E_USER_ERROR);
            exit;
        }
    }
}
function trace() { 
    $_8eff7488143342f815ddf2d63c506423 = '';
    for ($_cb66d5f29c9959086bc3e58c1f079f63=0; $_cb66d5f29c9959086bc3e58c1f079f63<func_num_args(); $_cb66d5f29c9959086bc3e58c1f079f63++) {
        if(is_array(func_get_arg($_cb66d5f29c9959086bc3e58c1f079f63))) {
            trace_r(func_get_arg($_cb66d5f29c9959086bc3e58c1f079f63));
        } else {
            $_8eff7488143342f815ddf2d63c506423 .= func_get_arg($_cb66d5f29c9959086bc3e58c1f079f63);
        }
        if($_cb66d5f29c9959086bc3e58c1f079f63 <= func_num_args()-2) { 
            $_8eff7488143342f815ddf2d63c506423.=' : '; 
        }
    }
    echo "<br><b>\r\r".$_8eff7488143342f815ddf2d63c506423."\r\r</b>";
}
function trace_r($_35be5c23f5f8d0c07055bdd685d7be7f="array is empty") {
    echo "<pre><b>\r\r"; print_r($_35be5c23f5f8d0c07055bdd685d7be7f);
    echo "\r\r</b></pre>"; 
}
?>
