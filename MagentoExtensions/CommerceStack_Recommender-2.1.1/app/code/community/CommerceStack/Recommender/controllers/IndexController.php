<?php
class CommerceStack_Recommender_IndexController extends Mage_Core_Controller_Front_Action
{
    public function requestUpdateAction()
    {
        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        /** @var $server CommerceStack_CsApiClient_Model_Server */
        $server = $dataHelper->getServer();
        $clientInfo = $dataHelper->getClientInfo();
        $server->post('update/'/* . "?XDEBUG_SESSION_START=PHPSTORM"*/, $clientInfo);
    }

    public function orderitemMaxIdAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $maxId = $dataHelper->getMaxId(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            $dataHelper->getTableNameSafe('sales/order_item'),
            'item_id');

        echo $maxId;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function orderitemAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $xml = $dataHelper->getTableUpdateAsXml(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            'item_id, order_id, parent_item_id, store_id, created_at, product_id, price',
            $dataHelper->getTableNameSafe('sales/order_item'),
            'item_id',
            $this->getRequest()->getParam('max_server_id'),
            'orderitem',
            $this->getRequest()->getParam('chunk_size')
        );

        echo $xml;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function logcustomerMaxIdAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $maxId = $dataHelper->getMaxId(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            $dataHelper->getTableNameSafe('log/customer'),
            'log_id');

        echo $maxId;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function logcustomerAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $xml = $dataHelper->getTableUpdateAsXml(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            'log_id, visitor_id, customer_id, store_id, login_at, logout_at',
            $dataHelper->getTableNameSafe('log/customer'),
            'log_id',
            $this->getRequest()->getParam('max_server_id'),
            'logcustomer',
            $this->getRequest()->getParam('chunk_size')
        );

        echo $xml;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function logurlinfoMaxIdAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $maxId = $dataHelper->getMaxId(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            $dataHelper->getTableNameSafe('log/url_info_table'),
            'url_id');

        echo $maxId;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function logurlinfoAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $xml = $dataHelper->getTableUpdateAsXml(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            'url_id, url',
            $dataHelper->getTableNameSafe('log/url_info_table'),
            'url_id',
            $this->getRequest()->getParam('max_server_id'),
            'logurlinfo',
            $this->getRequest()->getParam('chunk_size')
        );

        echo $xml;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function logurlMaxIdAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $maxId = $dataHelper->getMaxId(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            $dataHelper->getTableNameSafe('log/url_table'),
            'url_id');

        echo $maxId;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function logurlAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $xml = $dataHelper->getTableUpdateAsXml(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            'url_id, visitor_id, visit_time',
            $dataHelper->getTableNameSafe('log/url_table'),
            'url_id',
            $this->getRequest()->getParam('max_server_id'),
            'logurl',
            $this->getRequest()->getParam('chunk_size')
        );

        echo $xml;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function urlrewriteMaxIdAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $maxId = $dataHelper->getMaxId(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            $dataHelper->getTableNameSafe('core/url_rewrite') . " WHERE product_id IS NOT null",
            'url_rewrite_id');

        echo $maxId;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function urlrewriteAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        // "url_rewrite_id AS id" required for backward compatibility
        $xml = $dataHelper->getTableUpdateAsXml(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            'url_rewrite_id AS id, store_id, target_path, request_path, product_id',
            $dataHelper->getTableNameSafe('core/url_rewrite'),
            'url_rewrite_id',
            $this->getRequest()->getParam('max_server_id'),
            'urlrewrite',
            $this->getRequest()->getParam('chunk_size'),
            "product_id IS NOT null"
        );

        echo $xml;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function producturlMaxIdAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        $maxId = $dataHelper->getMaxId(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            (string)Mage::getConfig()->getTablePrefix() . 'catalog_product_entity_varchar v, ' . $dataHelper->getTableNameSafe('eav/attribute') . " eav WHERE v.attribute_id = eav.attribute_id AND eav.attribute_code='url_path'",
            'value_id');

        echo $maxId;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function producturlAction()
    {
        set_time_limit(1800);

        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');

        // "v.value_id AS id" required for backward compatibility
        $xml = $dataHelper->getTableUpdateAsXml(
            $this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            'v.value_id as id, v.entity_id as product_id, v.value as url_path',
            (string)Mage::getConfig()->getTablePrefix() . 'catalog_product_entity_varchar v, ' . $dataHelper->getTableNameSafe('eav/attribute') . ' eav',
            'v.value_id',
            $this->getRequest()->getParam('max_server_id'),
            'producturl',
            $this->getRequest()->getParam('chunk_size'),
            "v.attribute_id = eav.attribute_id AND eav.attribute_code='url_path'"
        );

        echo $xml;

        exit(); // Suppress any cache displays or other extension processing
    }

    public function syncAction()
    {
        /** @var $dataHelper CommerceStack_Recommender_Helper_Data */
        $dataHelper = Mage::helper('recommender');
        $dataHelper->sync($this->getRequest()->getParam('api_user'),
            $this->getRequest()->getParam('api_secret'),
            $this->getRequest()->getParam('key'),
            $this->getRequest()->getParam('value'));
    }

    public function marketbasketAction()
    {
        set_time_limit(1800);
        $xml = simplexml_load_string($this->getRequest()->getParam('recs'));
        $productLinks = Mage::getModel('recommender/product_link');
        $productLinks->updateFromXml($xml, 5); // Mage_Catalog_Model_Product_Link::LINK_TYPE_CROSSSELL. PHP 5.2.x doesn't allow accessing class const
    }

    public function alsoviewedAction()
    {
        set_time_limit(1800);
        $xml = simplexml_load_string($this->getRequest()->getParam('recs'));
        $productLinks = Mage::getModel('recommender/product_link');
        $productLinks->updateFromXml($xml, 1); // Mage_Catalog_Model_Product_Link::LINK_TYPE_RELATED. PHP 5.2.x doesn't allow accessing class const
    }
}