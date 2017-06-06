<?php
/**
 * Noovias_Extensions_Model_Factory
 *
 * NOTICE OF LICENSE
 *
 * Noovias_Extensions is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Noovias_Extensions is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Noovias_Extensions. If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Noovias_Extensions to newer
 * versions in the future. If you wish to customize Noovias_Extensions for your
 * needs please refer to http://www.noovias.com for more information.
 *
 * @category       Noovias
 * @package        Noovias_Extensions
 * @copyright      Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license        <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link           http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Extensions
 * @copyright      Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author         noovias.com - Core Team <info@noovias.com>
 */
class Noovias_Extensions_Model_Factory
{
    /**
     * @var Noovias_Extensions_Helper_Data
     */
    protected $helper = null;

    /** @var Noovias_Extensions_Helper_Config */
    protected $helperConfig = null;

    /**
     * @param $configPath string path to a Magento config, e.g. namespace_module_section, it should contain at least values for "to", "copy_to", "from" and "template"
     * @return Noovias_Extensions_Model_Service_Email_Send
     */
    public function getServiceEmailSendByConfigPath($configPath)
    {
        $config = $this->helperConfig()->getConfigSendEmailByPath($configPath);

        $service = $this->getServiceEmailSend($config);
        return $service;
    }

    /**
     * @param Noovias_Extensions_Model_Config_Email_Interface $config
     * @return Noovias_Extensions_Model_Service_Email_Send
     */
    public function getServiceEmailSend(Noovias_Extensions_Model_Config_Email_Interface $config)
    {
        /** @var $service Noovias_Extensions_Model_Service_Email_Send */
        $service = Mage::getModel('noovias_extensions/service_email_send');
        $service->setConfig($config);
        return $service;
    }

    /**
     *
     * @return Mage_Core_Model_Email_Template
     */
    public function getModelEmailTemplate()
    {
        /** @var $model Mage_Core_Model_Email_Template */
        $model = Mage::getModel('core/email_template');
        return $model;
    }

    /**
     *
     * @return Noovias_Extensions_Helper_Data
     */
    public function helper()
    {
        if ($this->helper === null)
        {
            $this->helper = Mage::helper('noovias_extensions');
        }
        return $this->helper;
    }

    /**
     *
     * @return Noovias_Extensions_Helper_Config
     */
    public function helperConfig()
    {
        if ($this->helperConfig === null)
        {
            $this->helperConfig = Mage::helper('noovias_extensions/config');
        }
        return $this->helperConfig;
    }


    /**
     * @param Noovias_Extensions_Helper_Data $helper
     */
    public function setHelper(Noovias_Extensions_Helper_Data $helper)
    {
        $this->helper = $helper;
    }

}