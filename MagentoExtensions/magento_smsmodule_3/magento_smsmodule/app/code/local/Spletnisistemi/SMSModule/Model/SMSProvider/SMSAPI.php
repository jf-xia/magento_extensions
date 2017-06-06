<?php

class Spletnisistemi_SMSModule_Model_SMSProvider_SMSAPI extends Spletnisistemi_SMSModule_Model_SMSProvider_Abstract {
    
    public function sendSms($to) {
        
        if(!$this->checkIfNumberIsValid($to)) {
            throw new Spletnisistemi_SMSModule_Exception('To number format is not supported.');
        }
        
        $url = 'http://www.smsapi.si/poslji-sms';
        $data = array('un' => urlencode($this->_user),   //api username
                      'ps' => urlencode($this->_pass), //api pass
                      'from' => urlencode($this->_from),          //don't send as int
                      'to' => urlencode($to),        //don't send as int
                      'm' => urlencode($this->_msg),  //msg
                      'cc' => urlencode($this->_countryCode),              //don't send as int
                      'sid' => $this->_useSenderId,
        );

        $response = $this->doPostRequest($url, $data);
        return $response;
    }
    
    /**
     * Posts $data on $url and returns content of website
     * POST request was made to.
     * 
     * @param $url - url where we are posting data
     * @param $data - array with key => value pairs for post 
     *                e.g. array('un' => 'example', 'ps' => 'pass', ...)
     *
     * @return string
     */
    protected function doPostRequest($url, $data) {
        $ch=curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1) ;

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $result = curl_exec($ch);

        curl_close($ch);
        
        return $result;
    }
    
    public function handleResponseFromSentSms($response) {
        if(substr($response, 0, 2) == '-1') {
            return $response;
        }

        return true;
    }
}