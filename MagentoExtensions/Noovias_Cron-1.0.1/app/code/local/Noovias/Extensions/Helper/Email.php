<?php
/**
 * Noovias_Extensions_Helper_Email
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
 * @category    Noovias
 * @package     Noovias_Extensions
 * @copyright   Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license     <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link        http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Extensions
 * @copyright      Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author      noovias.com - Core Team <info@noovias.com>
 */
class Noovias_Extensions_Helper_Email extends Mage_Core_Helper_Abstract
{
    protected $logFileName = 'noovias_extensions_email.log';

    /** @var Noovias_Extensions_Model_Factory */
    protected $factory = null;


    /**
     * Send Mail Notification to Support
     *
     * @deprecated Use Noovias_Extensions_Model_Service_Email_Send instead.
     *
     * @param string $pathConfig
     * @param array  $data array(message, short, long, request)
     * @param array  $attachments
     * @return bool
     */
    public function sendMail($pathConfig, $data, array $attachments = array())
    {
        $service = $this->getFactory()->getServiceEmailSendByConfigPath($pathConfig);

        $result = $service->execute($data, $attachments);

        return $result;
    }

    /**
     * @deprecated Use Noovias_Extensions_Model_Service_Email_Send instead.
     *
     * @param string $recipientEmailAdress
     * @param string $recipientName
     * @param string $pathConfig (Magento config path, needs options: "enabled" (bool), "from", "to" (source_model: adminhtml/system_config_source_email_identity), 'template', 'copy_to'
     * @param array  $data
     * @param array  $attachments
     * @return bool
     */
    public function sendMailToCustomer($recipientEmailAdress, $recipientName, $pathConfig, array $data = array(), array $attachments = array())
    {
        $service = $this->getFactory()->getServiceEmailSendByConfigPath($pathConfig);

        $result = $service->execute($data, $attachments, $recipientEmailAdress, $recipientName);

        return $result;
    }

    /**
     * create Email Attachment
     *
     * @param <type> $body
     * @param <type> $encoding
     * @param <type> $mimeType
     * @param <type> $disposition
     * @param <type> $filename
     * @return Zend_Mime_Part
     */
    public function createEmailAttachment($body, $encoding, $mimeType, $disposition, $filename)
    {
        $mp = new Zend_Mime_Part($body);
        $mp->encoding = $encoding;
        $mp->type = $mimeType;
        $mp->disposition = $disposition;
        $mp->filename = $filename;

        return $mp;
    }

    /**
     *
     * @return Mage_Core_Model_Email_Template
     */
    public function getModelEmailTemplate()
    {
        return Mage::getModel('core/email_template');
    }

    /**
     * @param Noovias_Extensions_Model_Factory $factory
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Noovias_Extensions_Model_Factory
     */
    public function getFactory()
    {
        if($this->factory === NULL)
            $this->factory = new Noovias_Extensions_Model_Factory();
        return $this->factory;
    }

}