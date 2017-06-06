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

class Magpleasure_Guestbook_Model_Mysql4_Message_Collection
    extends Magpleasure_Common_Model_Resource_Collection_Abstract
{

    public function _construct()
    {
        $this->_init("guestbook/message");
    }

    public function addStoreFilter($storeId)
    {
        $this->getSelect()
            ->where("main_table.store_id = ?", $storeId)
        ;
        return $this;
    }

    public function addActiveFilter($ownerSessionId = null)
    {
        if ($ownerSessionId){
            $activeStatus = Magpleasure_Guestbook_Model_Message::STATUS_APPROVED;
            $pendingStatus = Magpleasure_Guestbook_Model_Message::STATUS_PENDING;
            $this->getSelect()
                ->where(new Zend_Db_Expr("(main_table.status = '{$activeStatus}') OR ((main_table.status = '{$pendingStatus}') AND (main_table.session_id = '$ownerSessionId'))"))
            ;


        } else {
            $this->addFieldToFilter('status', Magpleasure_Guestbook_Model_Message::STATUS_APPROVED);
        }
        return $this;
    }

    public function addReplyTo()
    {
        $this->getSelect()
            ->joinLeft(array('replied'=>$this->getMainTable()), "replied.message_id = main_table.reply_to", array('reply_to_text'=>'replied.message'))
        ;
        return $this;
    }


    public function addReplyToTextFilter($filter)
    {
        $this->getSelect()
            ->where("replied.message LIKE ('%{$filter}%')")
        ;
        return $this;
    }


    public function addMessageTextFilter($filter)
    {
        $this->getSelect()
            ->where("main_table.message LIKE ('%{$filter}%')")
        ;
        return $this;
    }

    public function setDateOrder($dir = 'ASC')
    {
        $this->getSelect()
            ->order("main_table.created_at {$dir}");
        return $this;
    }

    public function setNotReplies()
    {
        $this->getSelect()
            ->where("main_table.reply_to IS NULL")
        ;
        return $this;
    }

    public function setReplyToFilter($commentId)
    {
        $this->getSelect()
            ->where("main_table.reply_to = ?", $commentId)
        ;
        return $this;
    }

    public function addStatusFilter($ststusId)
    {
        $this->getSelect()
            ->where("main_table.status = ?", $ststusId)
        ;
        return $this;
    }
}
	 