<?php 
/*                                                                       *
* This script is part of the epoq Recommendation Service project         *
*                                                                        *
* epoqinterface is free software; you can redistribute it and/or modify  *
* it under the terms of the GNU General Public License version 2 as      *
* published by the Free Software Foundation.                             *
*                                                                        *
* This script is distributed in the hope that it will be useful, but     *
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
* TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
* Public License for more details.                                       *
*                                                                        *
* @version $Id: Abstract.php 666 2011-07-06 13:44:33Z rieker $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Model_Recommendation_Abstract extends Flagbit_EpoqInterface_Model_Abstract {


    /**
     * Constructor
     *
     * @param string|Zend_Uri_Http $uri URI for the web service
     * @return void
     */
    public function __construct()
    {   
        $args = func_get_args();
        if (empty($args[0])) {
            $args[0] = array();
        }
        $this->_data = $args[0];

        $this->_construct();

        // get Data
        $result = $this->_doRequest();
        if (!$result instanceof Zend_Rest_Client_Result) {
            return;
        }

        // generate product ID array
        $productIds = array();
        if ($result->getIterator() instanceof SimpleXMLElement) {
            foreach ($result->getIterator()->recommendation as $product) {
                $productIds[] = (int) $product->productId;
            }
        }

        // set Data
        $this->setProductIds($productIds);
        $this->setRecommendationId((string) $result->getIterator()->recommendationId);  

        $this->getSession()->setLastRecommentationId($this->getRecommendationId());
        $this->getSession()->setLastRecommentationProducts($this->getProductIds());
    }

    /**
     * get Product Collection
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function getCollection()
    {
        /*@var $collection Flagbit_EpoqInterface_Model_Rescource_Eav_Mysql4_Product_Collection */
        $collection = Mage::getResourceModel('epoqinterface/product_collection');
        $collection->setProductIds($this->getProductIds());
        return $collection;
    }    

    /**
     * return Zend Rest Client
     *
     * @return Zend_Rest_Client
     */
    public function getRestClient()
    {
        if (!$this->_restClient instanceof Zend_Rest_Client) {
            if (array_key_exists('action', $this->getData()) && $this->getData('action') == 'processCart') {
                $url = $this->getRestUrl().'processCart?'.$this->_httpBuildQuery($this->getParamsArray());
            } else {
                $url = $this->getRestUrl().'getRecommendationsFor'.$this->_getRecommendationFor.'?'.$this->_httpBuildQuery($this->getParamsArray());
            }

            $this->_restClient = new Zend_Rest_Client($url);
            $this->_restClient->getHttpClient()->setConfig(
                array(
                    'timeout' => Mage::getStoreConfig(self::XML_TIMEOUT_PATH)
                )
            );
        }
        return $this->_restClient;
    }

    protected function _httpBuildQuery($array, $previousKey='')
    {
        $string = '';
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $string .= ($string ? '&' : '').$this->_httpBuildQuery($value, $key);
                continue;
            }
            $string .= ($string ? '&' : '').(is_numeric($key) && $previousKey ? $previousKey : $key ).($value ? '='.urlencode($value) : '');
        }
        return $string;
    }

    protected function getParamsArray()
    {
        $variables = array(
            'tenantId'        => Mage::getStoreConfig(self::XML_TENANT_ID_PATH),
            'sessionId'        => Mage::getSingleton('core/session')->getSessionId(),
            'demo'            => Mage::getStoreConfig(self::XML_DEMO_PATH) ? 6 : 0,
            'widgetTheme'    => 'xml',   
            'rules'            => $this->getRules()
        ); 

        if ($customerId = Mage::getSingleton('customer/session')->getId()) {
            $variables['customerId'] = $customerId;
        }

        return $variables;
    }
}