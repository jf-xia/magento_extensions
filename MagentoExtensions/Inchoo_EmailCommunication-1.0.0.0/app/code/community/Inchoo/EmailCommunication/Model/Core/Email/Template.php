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
class Inchoo_EmailCommunication_Model_Core_Email_Template extends Mage_Core_Model_Email_Template
{
    public function send($email, $name = null, array $variables = array())
    {
        //START Added as a part of Inchoo_EmailCommunication extension
        $emailCommunication = Mage::helper('inchoo_email_communication');
        if ($emailCommunication->getConfigEnabled() !== true) {
            return parent::send($email, $name, $variables);
        }
        //END Added as a part of Inchoo_EmailCommunication extension

        if (!$this->isValidForSend()) {
            Mage::logException(new Exception('This letter cannot be sent.')); // translation is intentionally omitted
            return false;
        }

        $emails = array_values((array)$email);
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);
        foreach ($emails as $key => $email) {
            if (!isset($names[$key])) {
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        $variables['email'] = reset($emails);
        $variables['name'] = reset($names);

        ini_set('SMTP', Mage::getStoreConfig('system/smtp/host'));
        ini_set('smtp_port', Mage::getStoreConfig('system/smtp/port'));

        $mail = $this->getMail();

        $setReturnPath = Mage::getStoreConfig(self::XML_PATH_SENDING_SET_RETURN_PATH);
        switch ($setReturnPath) {
            case 1:
                $returnPathEmail = $this->getSenderEmail();
                break;
            case 2:
                $returnPathEmail = Mage::getStoreConfig(self::XML_PATH_SENDING_RETURN_PATH_EMAIL);
                break;
            default:
                $returnPathEmail = null;
                break;
        }

        //START Added as a part of Inchoo_EmailCommunication extension
        if ($emailCommunication->getConfigEnabled() == true) {
            $config = array(
                'auth' => $emailCommunication->getConfigAuth(),
                'ssl' => $emailCommunication->getConfigSsl(),
                'username' => $emailCommunication->getConfigUsername(),
                'password' => $emailCommunication->getConfigPassword(),
                'name' => $emailCommunication->getConfigName(),
                'port' => $emailCommunication->getConfigPort(),
            );

            $transport = new Zend_Mail_Transport_Smtp($emailCommunication->getConfigHost(), $config);

            $mail->setDefaultTransport($transport);
        }
        //END Added as a part of Inchoo_EmailCommunication extension

        //if ($returnPathEmail !== null) {
        //START Added as a part of Inchoo_EmailCommunication extension
        if ($returnPathEmail !== null && $emailCommunication->getConfigEnabled() == false) {
            //END Added as a part of Inchoo_EmailCommunication extension
            $mailTransport = new Zend_Mail_Transport_Sendmail("-f".$returnPathEmail);
            Zend_Mail::setDefaultTransport($mailTransport);
        }

        foreach ($emails as $key => $email) {
            $mail->addTo($email, '=?utf-8?B?' . base64_encode($names[$key]) . '?=');
        }

        $this->setUseAbsoluteLinks(true);
        $text = $this->getProcessedTemplate($variables, true);

        if($this->isPlain()) {
            $mail->setBodyText($text);
        } else {
            $mail->setBodyHTML($text);
        }

        $mail->setSubject('=?utf-8?B?' . base64_encode($this->getProcessedTemplateSubject($variables)) . '?=');
        $mail->setFrom($this->getSenderEmail(), $this->getSenderName());

        try {
            $mail->send();
            $this->_mail = null;

            //START Added as a part of Inchoo_EmailCommunication extension
            if ($emailCommunication->getConfigEnabled() == true) {
                $inchooEmailCommunicationLog = Mage::getModel('inchoo_email_communication/log');
                $inchooEmailCommunicationLog->setCreatedAt(new Zend_Db_Expr('NOW()'));
                $inchooEmailCommunicationLog->setStatus(Inchoo_EmailCommunication_Model_Log::STATUS_SUCCESS);
                $inchooEmailCommunicationLog->setToEmail(implode(',', $emails));
                $inchooEmailCommunicationLog->setSubject($this->getProcessedTemplateSubject($variables));
                /* $inchooEmailCommunicationLog->setBody((($mail->getBodyText()) ? $mail->getBodyText(true) : $mail->getBodyHTML(true))); */

                try {
                    $inchooEmailCommunicationLog->save();
                } catch (Exception $eiecl) {
                    Mage::logException($eiecl);
                }
            }
            //END Added as a part of Inchoo_EmailCommunication extension
        }
        catch (Exception $e) {
            $this->_mail = null;
            Mage::logException($e);

            //START Added as a part of Inchoo_EmailCommunication extension
            if ($emailCommunication->getConfigEnabled() == true) {
                $inchooEmailCommunicationLog = Mage::getModel('inchoo_email_communication/log');
                $inchooEmailCommunicationLog->setCreatedAt(new Zend_Db_Expr('NOW()'));
                $inchooEmailCommunicationLog->setStatus(Inchoo_EmailCommunication_Model_Log::STATUS_FAILURE);
                $inchooEmailCommunicationLog->setToEmail(implode(',', $emails));
                $inchooEmailCommunicationLog->setSubject($this->getProcessedTemplateSubject($variables));
                /* $inchooEmailCommunicationLog->setBody((($mail->getBodyText()) ? $mail->getBodyText(true) : $mail->getBodyHTML(true))); */

                try {
                    $inchooEmailCommunicationLog->save();
                } catch (Exception $eiecl) {
                    Mage::logException($eiecl);
                }
            }
            //END Added as a part of Inchoo_EmailCommunication extension

            return false;
        }

        return true;
    }    
}