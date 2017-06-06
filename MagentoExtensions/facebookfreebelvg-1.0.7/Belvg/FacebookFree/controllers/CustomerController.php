<?php

/**
 * BelVG LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 *
  /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 * *************************************** */
/* This package designed for Magento COMMUNITY edition
 * BelVG does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BelVG does not provide extension support in case of
 * incorrect edition usage.
  /***************************************
 *         DISCLAIMER   *
 * *************************************** */
/* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 * ****************************************************
 * @category   Belvg
 * @package    Belvg_FacebookFree
 * @copyright  Copyright (c) 2010 - 2011 BelVG LLC. (http://www.belvg.com)
 * @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
 */

class Belvg_FacebookFree_CustomerController extends Mage_Core_Controller_Front_Action {

    public function LoginAction()
    {

        $me = null;

        $cookie = $this->get_facebook_cookie(Mage::getStoreConfig('facebookfree/settings/appid'), Mage::getStoreConfig('facebookfree/settings/secret'));

        $me = json_decode($this->getFbData('https://graph.facebook.com/me?access_token=' . $cookie['access_token']));

        if (!is_null($me)) {
			$me = (array)$me;
            $session = Mage::getSingleton('customer/session');

            $db_read = Mage::getSingleton('core/resource')->getConnection('facebookfree_read');
            $tablePrefix = (string) Mage::getConfig()->getTablePrefix();
            $sql = 'SELECT `customer_id`
					FROM `' . $tablePrefix . 'belvg_facebook_customer`
					WHERE `fb_id` = ' . $me['id'] . '
					LIMIT 1';
            $data = $db_read->fetchRow($sql);

            if ($data) {
                $session->loginById($data['customer_id']);
            } else {
                $sql = 'SELECT `entity_id`
						FROM `' . $tablePrefix . 'customer_entity`
						WHERE email = "' . $me['email'] . '"
						AND store_id = "'.Mage::app()->getStore()->getStoreId().'"
						AND website_id = "'.Mage::getModel('core/store')->load(Mage::app()->getStore()->getStoreId())->getWebsiteId().'"
						LIMIT 1';
                $r = $db_read->fetchRow($sql);

                if ($r) {
                    $db_write = Mage::getSingleton('core/resource')->getConnection('facebookfree_write');
                    $sql = 'INSERT INTO `' . $tablePrefix . 'belvg_facebook_customer`
                                                    VALUES (' . $r['entity_id'] . ', ' . $me['id'] . ')';
                    $db_write->query($sql);
                    $session->loginById($r['entity_id']);
                } else {
                    $this->_registerCustomer($me, $session);
                }
            }
            $this->_loginPostRedirect($session);
        }
    }

    public function LogoutAction()
    {
        $session = Mage::getSingleton('customer/session');
        $session->logout()
                ->setBeforeAuthUrl(Mage::getUrl());

        $this->_redirect('customer/account/logoutSuccess');
    }

    private function _registerCustomer($data, &$session)
    {
        $customer = Mage::getModel('customer/customer')->setId(null);
        $customer->setData('firstname', $data['first_name']);
        $customer->setData('lastname', $data['last_name']);
        $customer->setData('email', $data['email']);
        $customer->setData('password', md5(time() . $data['id'] . $data['locale']));
        $customer->setData('is_active', 1);
        $customer->setData('confirmation', null);
        $customer->setConfirmation(null);
        $customer->getGroupId();
        $customer->save();

        Mage::getModel('customer/customer')->load($customer->getId())->setConfirmation(null)->save();
        $customer->setConfirmation(null);
        $session->setCustomerAsLoggedIn($customer);
        $customer_id = $session->getCustomerId();
        $db_write = Mage::getSingleton('core/resource')->getConnection('facebookfree_write');
        $tablePrefix = (string) Mage::getConfig()->getTablePrefix();
        $sql = 'INSERT INTO `' . $tablePrefix . 'belvg_facebook_customer`
				VALUES (' . $customer_id . ', ' . $data['id'] . ')';
        $db_write->query($sql);
    }

    private function _loginPostRedirect(&$session)
    {

        if ($referer = $this->getRequest()->getParam(Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME)) {
            $referer = Mage::helper('core')->urlDecode($referer);
            if ((strpos($referer, Mage::app()->getStore()->getBaseUrl()) === 0)
                    || (strpos($referer, Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, true)) === 0)) {
                $session->setBeforeAuthUrl($referer);
            } else {
                $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
            }
        } else {
            $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
        }
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

    private function get_facebook_cookie($app_id, $app_secret)
    {
        if ($_COOKIE['fbsr_' . $app_id] != '') {
            return $this->get_new_facebook_cookie($app_id, $app_secret);
        } else {
            return $this->get_old_facebook_cookie($app_id, $app_secret);
        }
    }

    private function get_old_facebook_cookie($app_id, $app_secret)
    {
        $args = array();
        parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
        ksort($args);
        $payload = '';
        foreach ($args as $key => $value) {
            if ($key != 'sig') {
                $payload .= $key . '=' . $value;
            }
        }
        if (md5($payload . $app_secret) != $args['sig']) {
            return array();
        }
        return $args;
    }

    private function get_new_facebook_cookie($app_id, $app_secret)
    {
        $signed_request = $this->parse_signed_request($_COOKIE['fbsr_' . $app_id], $app_secret);
        // $signed_request should now have most of the old elements
        $signed_request['uid'] = $signed_request['user_id']; // for compatibility 
        if (!is_null($signed_request)) {
            // the cookie is valid/signed correctly
            // lets change "code" into an "access_token"
			$access_token_response = $this->getFbData("https://graph.facebook.com/oauth/access_token?client_id=$app_id&redirect_uri=&client_secret=$app_secret&code=$signed_request[code]");
			parse_str($access_token_response);
			$signed_request['access_token'] = $access_token;
			$signed_request['expires'] = time() + $expires;
        }

        return $signed_request;
    }

    private function parse_signed_request($signed_request, $secret)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    private function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
	
	private function getFbData($url)
	{
		$data = null;

		if (ini_get('allow_url_fopen') && function_exists('file_get_contents')) {
			$data = file_get_contents($url);
		} else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($ch);
		}
		return $data;
	}

}