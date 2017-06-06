<?php

class Belvg_FacebookFree_Model_Request_Cookie extends Mage_Core_Model_Cookie
{

    /**
     * Original FB cookie
     *
     * @var string
     */
    private $_fb_cookie = null;

    /**
     * get FB cookie
     */
    public function __construct()
    {
        $cookie_name = 'fbsr_' . Mage::helper('facebookfree')->getAppId();
        $this->_fb_cookie = $this->get($cookie_name);
    }
    /**
     * get FB cookie
     *
     * @return string
     */
    public function getFbCookie()
    {
        return $this->_fb_cookie;
    }

    /**
     * Decode and parce FB cookie
     *
     * @return array|NULL
     * @throws Exception
     */
    private function parseCookie()
    {
        if (!empty($this->_fb_cookie)) {
            if (list($encoded_sig, $payload) = explode('.', $this->_fb_cookie, 2)) {

                // decode the data
                $sig = $this->base64_url_decode($encoded_sig);
                $data = json_decode($this->base64_url_decode($payload), TRUE);

                if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
                    throw new Exception('Unknown algorithm. Expected HMAC-SHA256');
                    return NULL;
                }

                $secret = Mage::helper('facebookfree')->getAppSecret();
                // Adding the verification of the signed_request below
                $expected_sig = hash_hmac('sha256', $payload, $secret, TRUE);
                if ($sig !== $expected_sig) {
                    throw new Exception('Bad Signed JSON signature!');
                    return NULL;
                }

                return $data;
            }
        }

        return NULL;
    }

    /**
     * Getter for parceCookie() method
     *
     * @return array
     */
    public function getParsedCookie()
    {
        return $this->parseCookie();
    }

    /**
     * See http://developers.facebook.com/docs/authentication/signed_request/ for more info
     *
     * @param string $input
     * @return string
     */
    private function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

}