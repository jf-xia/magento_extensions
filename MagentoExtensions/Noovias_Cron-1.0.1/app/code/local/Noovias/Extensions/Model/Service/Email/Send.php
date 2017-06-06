<?php
/**
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
 * @copyright      Copyright (c) 2011 <info@noovias.com> - noovias.com
 * @license        <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link           http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Extensions
 * @copyright      Copyright (c) 2011 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author         noovias.com - Core Team <info@noovias.com>
 *
 * @todo           refactor: this Service should be called Noovias_Extensions_Model_Service_SendEmail
 * @info           A Service should always have a name that can be pronouncted
 */
class Noovias_Extensions_Model_Service_Email_Send
    extends Noovias_Extensions_Model_Service_Abstract
{
    /** @var Noovias_Extensions_Model_Config_Email_Interface */
    protected $config = null;

    /**
     * Send Mail Notification to Support
     *
     * @todo this method should be the single point of entry to execute this classes purpose
     *
     * @param array $data array(message, short, long, request)
     * @param array $attachments
     *
     * @return bool
     */
    public function execute(array $data, array $attachments = array(), $recipientMailAddress = '', $recipientName = '')
    {
        $config = $this->getConfig();

        if ($config->isEnabled() === FALSE) {
            return FALSE;
        }

        try
        {
            $sendTo = $this->collectRecipients();

            if (trim($recipientMailAddress) != '') {
                // Add the customer as a recipient:
                $mainRecipient = array('name' => $recipientName,
                    'email' => $recipientMailAddress);

                array_unshift($sendTo, $mainRecipient);
            }

            $return = $this->sendMail($data, $attachments, $sendTo);

            return $return;
        }
        catch (Exception $e)
        {
            Mage::log('Error Sending Mail ' . $e->getMessage(), Zend_Log::ERR, $config->getLogFileName());
            return FALSE;
        }
    }

    /**
     * @param array $data
     * @param array $attachments
     * @param array $sendTo array of arrays, e.g. array(0 => array('email' => 'XXXX@test.com, 'name' => 'YYYYY'))
     *
     * @return bool
     */
    protected function sendMail(array $data, array $attachments, array $sendTo)
    {
        if (count($sendTo) < 1) {
            // No mail recipients, bye.
            return FALSE;
        }

        // Get Sender and Receiver
        $transactionalEmails = $this->helperConfig()->getConfigTransactionalEmails();
        $from = $transactionalEmails['ident_' . $this->getConfig()->getFrom()];

        if (!is_array($from)) {
            // Wrong or no magento configuration for emails, bye.
            return FALSE;
        }

        $mail = $this->loadEmailTemplate();
        $mail->setSenderEmail($from['email']); // magic
        $mail->setSenderName($from['name']); // magic


        $return = FALSE;
        // Send Mail to all recipients
        foreach ($sendTo as $to)
        {
            foreach ($attachments as $key => $attachment)
            {
                $mail->getMail()->addAttachment($attachment);
            }
            $return = $mail->send($to['email'], $to['name'], $data);
        }

        return $return;
    }

    /**
     * @todo refactor: this method should also no longer be need here
     * @todo refactor: this method should go to the Helper which creates the config object
     *
     * @return array
     */
    protected function collectRecipients()
    {
        $transactionalEmails = $this->helperConfig()->getConfigTransactionalEmails();

        $sendTo = array();

        // Regular recipient, from Mage Config
        if (array_key_exists('ident_' . $this->getConfig()->getTo(), $transactionalEmails)) {
            $sendTo[] = $transactionalEmails['ident_' . $this->getConfig()->getTo()];
        }

        // Merge copy recipients into the array
        $copyTo = $this->getConfig()->getCopyTo();
        if (isset($copyTo)) {
            $copyTo = explode(',', $copyTo);
            foreach ($copyTo as $email)
            {
                if (trim($email) != '') {
                    $sendTo[] = array(
                        'name' => null,
                        'email' => $email
                    );
                }
            }
        }

        return $sendTo;
    }

    /**
     * @return Mage_Core_Model_Email_Template
     */
    protected function loadEmailTemplate()
    {
        // Init Mail Template
        $mail = $this->getFactory()->getModelEmailTemplate();

        $templateId = $this->getConfig()->getTemplate();

        if (is_numeric($templateId)) {
            $mail->load($templateId);
        }
        else
        {
            $mail->loadDefault($templateId);
        }

        return $mail;
    }

    /**
     * @param Noovias_Extensions_Model_Config_Email_Interface $config
     */
    public function setConfig(Noovias_Extensions_Model_Config_Email_Interface $config)
    {
        $this->config = $config;
    }

    /**
     * @return Noovias_Extensions_Model_Config_Email_Interface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Noovias_Extensions_Helper_Config
     */
    protected function helperConfig()
    {
        return $this->getFactory()->helperConfig();
    }


}
