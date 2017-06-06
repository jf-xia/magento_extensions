<?php 

/**
 * Pest is a REST client for PHP.
 *
 * See http://github.com/educoder/pest for details.
 *
 * This code is licensed for use, modification, and distribution
 * under the terms of the MIT License (see http://en.wikipedia.org/wiki/MIT_License)
 */
class CommerceStack_CsApiClient_Model_Server extends Mage_Core_Model_Abstract
{
  public $curl_opts = array(
  	CURLOPT_RETURNTRANSFER => true,  // return result instead of echoing
  	CURLOPT_SSL_VERIFYPEER => false,
  	CURLOPT_FOLLOWLOCATION => false,  // follow redirects, Location: headers
  	CURLOPT_MAXREDIRS      => 10,     // but dont redirect more than 10 times
  );

  public $base_url;
  
  public $last_response;
  public $last_request;

  protected $_clientModuleName;
  protected $_clientModuleVersion;

  public function setClientModuleName($name)
  {
      $this->_clientModuleName = $name;
  }

  public function setClientModuleVersion($version)
  {
      $this->_clientModuleVersion = $version;
  }

  protected function _getAuthUrl($url)
  {
      try
      {
          $account = Mage::getModel('csapiclient/account');
          $account->setClientModuleName($this->_clientModuleName);
          $account->setClientModuleVersion($this->_clientModuleVersion);
      }
      catch(CsApiClient_Server_ServerError $e)
      {
          $this->_reportException($e);
      }

      return $account->appendAuthToUri($url);
  }
  
  public function __construct() {
    if (!function_exists('curl_init')) {
  	    throw new Exception('CURL module not available! Pest requires CURL. See http://php.net/manual/en/book.curl.php');
  	}
    
  	$this->base_url = Mage::getStoreConfig('csapiclient/api/base_uri');
  }
 
  
  public function get($url, $requireAuthentication = true)
  {
    if($requireAuthentication)
    {
        $url = $this->_getAuthUrl($url);
    }

    $curl = $this->prepRequest($this->curl_opts, $url);
    $body = $this->doRequest($curl);
    
    $body = $this->processBody($body);
    
    return $body;
  }
  
  public function post($url, $data, $headers=array(), $requireAuthentication = true)
  {
      if($requireAuthentication)
      {
          $url = $this->_getAuthUrl($url);
      }

      $data = (is_array($data)) ? http_build_query($data) : $data;
        
    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_CUSTOMREQUEST] = 'POST';
    $headers[] = 'Content-Length: '.strlen($data);
    $curl_opts[CURLOPT_HTTPHEADER] = $headers;
    $curl_opts[CURLOPT_POSTFIELDS] = $data;
    
    $curl = $this->prepRequest($curl_opts, $url);
    $body = $this->doRequest($curl);
    
    $body = $this->processBody($body);
    
    return $body;
  }
  
  public function put($url, $data, $headers=array()) {
    $data = (is_array($data)) ? http_build_query($data) : $data; 
    
    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
    $headers[] = 'Content-Length: '.strlen($data);
    $curl_opts[CURLOPT_HTTPHEADER] = $headers;
    $curl_opts[CURLOPT_POSTFIELDS] = $data;
    
    $curl = $this->prepRequest($curl_opts, $url);
    $body = $this->doRequest($curl);
    
    $body = $this->processBody($body);
    
    return $body;
  }

  // Changed name to restdelete to avoid conflict with Mage_Core_Model_Abstract
  public function restdelete($url) {
    $curl_opts = $this->curl_opts;
    $curl_opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
    
    $curl = $this->prepRequest($curl_opts, $url);
    $body = $this->doRequest($curl);
    
    $body = $this->processBody($body);
    
    return $body;
  }
  
  public function lastBody() {
    return $this->last_response['body'];
  }
  
  public function lastStatus() {
    return $this->last_response['meta']['http_code'];
  }
  
  protected function processBody($body) {
    // Override this in classes that extend Pest.
    // The body of every GET/POST/PUT/DELETE response goes through 
    // here prior to being returned.
  	return $body;
  }
  
  protected function processError($body) {
    // Override this in classes that extend Pest.
    // The body of every erroneous (non-2xx/3xx) GET/POST/PUT/DELETE  
    // response goes through here prior to being used as the 'message'
    // of the resulting CsApiClient_Server_Exception
    return $body;
  }

  
  protected function prepRequest($opts, $url) {
    if (strncmp($url, $this->base_url, strlen($this->base_url)) != 0) {
      $url = $this->base_url . $url;
    }
    $curl = curl_init($url);
    
    foreach ($opts as $opt => $val){
      curl_setopt($curl, $opt, $val);}
      
    $this->last_request = array(
      'url' => $url
    );
    
    if (isset($opts[CURLOPT_CUSTOMREQUEST]))
      $this->last_request['method'] = $opts[CURLOPT_CUSTOMREQUEST];
    else
      $this->last_request['method'] = 'GET';
    
    if (isset($opts[CURLOPT_POSTFIELDS]))
      $this->last_request['data'] = $opts[CURLOPT_POSTFIELDS];
    
    return $curl;
  }
  
  private function doRequest($curl) {
    $body = curl_exec($curl);
    $meta = curl_getinfo($curl);
    
    $this->last_response = array(
      'body' => $body,
      'meta' => $meta
    );
    
    curl_close($curl);

    $this->checkLastResponseForError();
    
    return $body;
  }
  
  private function checkLastResponseForError() {
    $meta = $this->last_response['meta'];
    $body = $this->last_response['body'];
    
    if (!$meta)
      return;
    
    $err = null;
    switch ($meta['http_code']) {
      case 400:
        Mage::log("400 error: $body", null, 'recommender.log');
        throw new CsApiClient_Server_BadRequest($this->processError($body));
        break;
      case 401:
        Mage::log("401 error: $body", null, 'recommender.log');
        throw new CsApiClient_Server_Unauthorized($this->processError($body));
        break;
      case 403:
         Mage::log("403 error: $body", null, 'recommender.log');
        throw new CsApiClient_Server_Forbidden($this->processError($body));
        break;
      case 404:
        Mage::log("404 error: $body", null, 'recommender.log');
        throw new CsApiClient_Server_NotFound($this->processError($body));
        break;
      case 405:
        Mage::log("405 error: $body", null, 'recommender.log');
        throw new CsApiClient_Server_MethodNotAllowed($this->processError($body));
        break;
      case 409:
        Mage::log("409 error: $body", null, 'recommender.log');
        throw new CsApiClient_Server_Conflict($this->processError($body));
        break;
      case 410:
        Mage::log("410 error: $body", null, 'recommender.log');
        throw new CsApiClient_Server_Gone($this->processError($body));
        break;
      case 422:
        // Unprocessable Entity -- see http://www.iana.org/assignments/http-status-codes
        // This is now commonly used (in Rails, at least) to indicate
        // a response to a request that is syntactically correct,
        // but semantically invalid (for example, when trying to 
        // create a resource with some required fields missing)
        Mage::log("422 error: $body", null, 'recommender.log');
        throw new CsApiClient_Server_InvalidRecord($this->processError($body));
        break;
      default:
        if ($meta['http_code'] >= 400 && $meta['http_code'] <= 499)
        {
          Mage::log("Generic client error " . $meta['http_code'] . ": $body", null, 'recommender.log');
          throw new CsApiClient_Server_ClientError($this->processError($body));
        }
        elseif ($meta['http_code'] >= 500 && $meta['http_code'] <= 599)
        {
            Mage::log("Generic server error " . $meta['http_code'] . ": $body", null, 'recommender.log');
          throw new CsApiClient_Server_ServerError($this->processError($body));
        }
        elseif (!$meta['http_code'] || $meta['http_code'] >= 600)
        {
          Mage::log("Generic server unknown response " . $meta['http_code'] . ": $body", null, 'recommender.log');
          throw new CsApiClient_Server_UnknownResponse($this->processError($body));
        }
    }
  }

    public function reportException($e)
    {
        $this->curl_opts[CURLOPT_TIMEOUT] = 3;
        $errorReport = $e->getMessage() . "\n" . $e->getTraceAsString();

        try
        {
            $this->post("exception/", $errorReport);
        }
        catch(Exception $e)
        {
            //throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}


class CsApiClient_Server_Exception extends Exception { }
class CsApiClient_Server_UnknownResponse extends CsApiClient_Server_Exception { }

/* 401-499 */ class CsApiClient_Server_ClientError extends CsApiClient_Server_Exception {}
/* 400 */ class CsApiClient_Server_BadRequest extends CsApiClient_Server_ClientError {}
/* 401 */ class CsApiClient_Server_Unauthorized extends CsApiClient_Server_ClientError {}
/* 403 */ class CsApiClient_Server_Forbidden extends CsApiClient_Server_ClientError {}
/* 404 */ class CsApiClient_Server_NotFound extends CsApiClient_Server_ClientError {}
/* 405 */ class CsApiClient_Server_MethodNotAllowed extends CsApiClient_Server_ClientError {}
/* 409 */ class CsApiClient_Server_Conflict extends CsApiClient_Server_ClientError {}
/* 410 */ class CsApiClient_Server_Gone extends CsApiClient_Server_ClientError {}
/* 422 */ class CsApiClient_Server_InvalidRecord extends CsApiClient_Server_ClientError {}

/* 500-599 */ class CsApiClient_Server_ServerError extends CsApiClient_Server_Exception {}

?>
