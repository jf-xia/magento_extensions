<?php
/**
 * INCHOO's FREE EXTENSION DISCLAIMER
 *
 * Please do not edit or add to this file if you wish to upgrade Magento
 * or this extension to newer versions in the future.
 *
 * Inchoo developers (Inchooer's) give their best to conform to
 * "non-obtrusive, best Magento practices" style of coding.
 * However, Inchoo does not guarantee functional accuracy of specific
 * extension behavior. Additionally we take no responsibility for any
 * possible issue(s) resulting from extension usage.
 *
 * We reserve the full right not to provide any kind of support for our free extensions.
 *
 * You are encouraged to report a bug, if you spot any,
 * via sending an email to bugreport@inchoo.net. However we do not guaranty
 * fix will be released in any reasonable time, if ever,
 * or that it will actually fix any issue resulting from it.
 *
 * Thank you for your understanding.
 */

/**
 * @category Inchoo
 * @package Inchoo_EmailCommunication
 * @author Branko Ajzele <ajzele@gmail.com>
 * @copyright Inchoo <http://inchoo.net>
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_EmailCommunication_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONFIG_XML_PATH_ENABLED = 'system/inchoo_email_communication/is_enabled';

    const CONFIG_XML_PATH_USERNAME = 'system/inchoo_email_communication/username';

    const CONFIG_XML_PATH_PASSWORD = 'system/inchoo_email_communication/password';

    /* CONFIG_XML_PATH_AUTH: Zend_Mail_Transport_Smtp::$_auth => string => Authentication type OPTIONAL */
    const CONFIG_XML_PATH_AUTH = 'system/inchoo_email_communication/auth';

    /* CONFIG_XML_PATH_SSL: 'ssl' or 'tls' */
    const CONFIG_XML_PATH_SSL = 'system/inchoo_email_communication/ssl';

    /* CONFIG_XML_PATH_HOST: Zend_Mail_Transport_Smtp::$_host => string => Remote smtp hostname or i.p. */
    const CONFIG_XML_PATH_HOST = 'system/inchoo_email_communication/host';

    /* CONFIG_XML_PATH_NAME: Zend_Mail_Transport_Smtp::$_name => string => Local client hostname or i.p., default 'localhost' */
    const CONFIG_XML_PATH_NAME = 'system/inchoo_email_communication/name';

    /* CONFIG_XML_PATH_PORT: Zend_Mail_Transport_Smtp::$_port => integer|null => Port number */
    const CONFIG_XML_PATH_PORT = 'system/inchoo_email_communication/port';

    const CONFIG_XML_PATH_LOGGING = 'system/inchoo_email_communication/logging';

    /*

    Inner working of Zend_Mail_Transport_Smtp::_sendMail():

    ...
        $connectionClass = 'Zend_Mail_Protocol_Smtp';
        if ($this->_auth) {
            $connectionClass .= '_Auth_' . ucwords($this->_auth);
        }
        if (!class_exists($connectionClass)) {
            #require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($connectionClass);
        }
        $this->setConnection(new $connectionClass($this->_host, $this->_port, $this->_config));
        $this->_connection->connect();
    ...

    */

    public function getConfigEnabled()
    {
        $isEnabled = Mage::getStoreConfig(self::CONFIG_XML_PATH_ENABLED);
        return (($isEnabled == '1') ? true : false);
    }

    public function getConfigUsername()
    {
        return Mage::getStoreConfig(self::CONFIG_XML_PATH_USERNAME);
    }

    public function getConfigPassword()
    {
        return Mage::getStoreConfig(self::CONFIG_XML_PATH_PASSWORD);
    }

    public function getConfigAuth()
    {
        return Mage::getStoreConfig(self::CONFIG_XML_PATH_AUTH);
    }

    public function getConfigSsl()
    {
        return Mage::getStoreConfig(self::CONFIG_XML_PATH_SSL);
    }

    public function getConfigHost()
    {
        return Mage::getStoreConfig(self::CONFIG_XML_PATH_HOST);
    }

    public function getConfigName()
    {
        return Mage::getStoreConfig(self::CONFIG_XML_PATH_NAME);
    }

    public function getConfigPort()
    {
        return Mage::getStoreConfig(self::CONFIG_XML_PATH_PORT);
    }

    public function getConfigLogging()
    {
        return Mage::getStoreConfig(self::CONFIG_XML_PATH_LOGGING);
    }
}