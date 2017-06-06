<?php 

class CommerceStack_CsApiClient_Model_Account extends Mage_Core_Model_Abstract
{
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

    public function appendAuthToUri($uri)
    {
        $key = $this->getApiKey();
        $apiUser = $key['user'];
        $apiSecret = $key['secret'];

        if(!($apiUser && $apiSecret))
        {
            $newKey = $this->_createAccount();
            $apiUser = $newKey['api_user'];
            $apiSecret = $newKey['api_secret'];
        }
        
        $query = parse_url($uri, PHP_URL_QUERY);

        if($query) 
        {
            $uri .= "&api_user=$apiUser&api_secret=$apiSecret";
        }
        else 
        {
            $uri .= "?api_user=$apiUser&api_secret=$apiSecret";
        }
        
        return $uri;
    }
    
    protected function _createAccount()
    {
        $unsecureBaseUrl = Mage::getStoreConfig('web/unsecure/base_url');
        $secureBaseUrl = Mage::getStoreConfig('web/secure/base_url');
        $mageVersion = Mage::getVersion();
        $email = Mage::getStoreConfig('recommender/account/email');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	    $xml .= "<account>\n";
	    //$xml .= "<commercestack_recommender_version>{$this->_getRecommenderVersion()}</commercestack_recommender_version>\n";
	    $xml .= "<commercestack_" . $this->_clientModuleName . "_version>" . $this->_clientModuleVersion . "</commercestack_" . $this->_clientModuleName . "_version>\n";
	    $xml .= "<mage_version>$mageVersion</mage_version>\n";
	    $xml .= "<unsecure_base_url>$unsecureBaseUrl</unsecure_base_url>\n";
        $xml .= "<secure_base_url>$secureBaseUrl</secure_base_url>\n";
        $xml .= "<email>$email</email>\n";
	    $xml .= "</account>\n";
	    
	    $retries = 0;
        $server = Mage::getModel('csapiclient/server');

        $endPoint = Mage::getStoreConfig('csapiclient/api/create_account_uri');

        Mage::log("Account::_createAccount(): xml = \n $xml \n post endpoint: $endPoint \n", null, 'recommender.log');
        $response = $server->post($endPoint/* . "?XDEBUG_SESSION_START=PHPSTORM"*/, $xml, null, false);

        $xml = simplexml_load_string($response);

        if(!$xml)
        {
            Mage::log("Server did not respond to account creation request", null, 'recommender.log');
            throw new CsApiClient_Server_ServerError('Server did not respond to account creation request.');
        }

        $config = new Mage_Core_Model_Config();
        $config->saveConfig('csapiclient/api/user', (string)$xml->api_user);
        $config->saveConfig('csapiclient/api/secret', (string)$xml->api_secret);
        Mage::getConfig()->cleanCache();

        // $config->reinit() and Mage::app()->reinitStores() don't seem to
        // refresh the config object with these new values so we save a temporary
        // set to the registry because we need them later in the request
        Mage::register('recommender_api_user', (string)$xml->api_user);
        Mage::register('recommender_api_secret', (string)$xml->api_secret);

        return array('api_user' => (string)$xml->api_user, 'api_secret' => (string)$xml->api_secret);
    }
    
    public function getApiKey()
    {
        // First, try to get the api keys from the registry. They will
        // be there if we just created the api key in this request
        $apiUser = Mage::registry('recommender_api_user');
        $apiSecret = Mage::registry('recommender_api_secret');

        if(!($apiUser && $apiSecret))
        {
            $apiUser = Mage::getStoreConfig('csapiclient/api/user');
            $apiSecret = Mage::getStoreConfig('csapiclient/api/secret');
        }

        if(!($apiUser && $apiSecret))
        {
            // Fall back to legacy 'recommender' key
            $apiUser = Mage::getStoreConfig('recommender/api_user');
            $apiSecret = Mage::getStoreConfig('recommender/api_secret');
        }
        
        return array('user' => $apiUser, 'secret' => $apiSecret);
    }

    public function authenticate($apiUser, $apiSecret)
    {
        $key = $this->getApiKey();
        return $key['user'] == $apiUser && $key['secret'] == $apiSecret;
    }
    
    public function getApiKeyAsJson()
    {
        $key = $this->getApiKey();
        
        $retJson = array();
        $retJson['apiUser'] = $key['user'];
        $retJson['apiSecret'] = $key['secret'];
        
        return json_encode($retJson);
    }
}

