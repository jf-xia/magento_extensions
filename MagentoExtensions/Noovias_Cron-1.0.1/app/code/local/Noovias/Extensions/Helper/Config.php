<?php
/**
 * Noovias_Extensions_Helper_Config
 *
 * @category   Noovias
 * @package    Noovias_Extensions
 * @author     Noovias Core Team
 */
class Noovias_Extensions_Helper_Config
{
    const PATH_CONFIG_ROOT = 'noovias_extensions';

    /**
     * @param $path
     * @return Noovias_Extensions_Model_Config_Email
     */
    public function getConfigSendEmailByPath($path)
    {
        /**
         * @var $data array
         */
        $data = Mage::getStoreConfig($path);

        /** @var $config Noovias_Extensions_Model_Config_Email */
        $config = Mage::getModel('noovias_extensions/config_email', $data);

        return $config;
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function getConfig($path = '')
    {
        if ($path != '')
        {
            $path = '/' . $path;
        }
        $config = Mage::getStoreConfig(self::PATH_CONFIG_ROOT . $path);
        return $config;
    }

    /**
     * @return mixed
     */
    public function getConfigTransactionalEmails()
    {
        $config = Mage::getStoreConfig('trans_email');

        return $config;
    }

}
