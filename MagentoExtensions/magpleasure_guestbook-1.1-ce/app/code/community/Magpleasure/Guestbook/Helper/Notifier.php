<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Guestbook
 * @version    1.1
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Guestbook_Helper_Notifier extends Mage_Core_Model_Abstract
{
    /**
     * Helper
     * @return Magpleasure_Guestbook_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('guestbook');
    }

    public function notifyAboutPendingComment(Magpleasure_Guestbook_Model_Message $comment)
    {
        $storeId = Mage::app()->getStore()->getId();
        $receiver = Mage::getStoreConfig('guestbook/notify_admin_new_comment/receiver', $storeId);

        if (!$receiver){
            return $this;
        }


        $data = array();
        $data['message'] = $comment->getMessage();
        $data['comment'] = $comment;
        $data['store'] = Mage::app()->getStore();


        $template = Mage::getStoreConfig('guestbook/notify_admin_new_comment/email_template', $storeId);
        $sender = Mage::getStoreConfig('guestbook/notify_admin_new_comment/sender', $storeId);
        $receivers = explode(",", $receiver);

        foreach ($receivers as $receiver){
            if (trim($receiver)){
                /** @var Mage_Core_Model_Email_Template $mailTemplate  */
                $mailTemplate = Mage::getModel('core/email_template');
                try {
                    $mailTemplate
                        ->setDesignConfig(array('area' => 'frontend', 'store'=>$storeId))
                        ->sendTransactional(
                        $template,
                        $sender,
                        trim($receiver),
                        $this->_helper()->__('Administrator'),
                        $data,
                        $storeId
                    )
                    ;

                } catch (Exception $e) {

                    $this->_helper()->getCommon()->getException()->logException($e);
                }
            }
        }

        return $this;
    }

}