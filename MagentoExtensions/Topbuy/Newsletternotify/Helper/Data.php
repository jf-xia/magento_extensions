<?php

class Topbuy_Newsletternotify_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function saveEmailToDB($entrydate, $fromName, $fromEmail, $toName, $toEmail, 
            $subject, $emailBody, $attachment, $updateflag, $sendtime,$emailType){
        try {
            Mage::getModel('newsletternotify/emailsendbuffer')
                    ->setEntrydate($entrydate)
                    ->setFromname($fromName)
                    ->setFromemail($fromEmail)
                    ->setToemail($toEmail)
                    ->setSubject($subject)
                    ->setBody($emailBody)
                    ->setAttachment($attachment)
                    ->setUpdateflag($updateflag)
                    ->setSenddate($sendtime)
                    ->setEmailtype($emailType)
                    ->setSchedulesenddate(Mage::getModel('core/date')->date())
                    ->setToname($toName)
                    ->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('saveEmailToDB insert', $e->getMessage());
            return false;
        }
        return true;    
    }
}
