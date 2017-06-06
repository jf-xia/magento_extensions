<?php

class Magpleasure_Guestbook_Model_Message extends Magpleasure_Common_Model_Abstract
{
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    /**
     * Helper
     * @return Magpleasure_Guestbook_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('guestbook');
    }

    protected function _construct()
    {
        $this->_init("guestbook/message");

    }

    public function getOptionsArray()
    {
        return array(
            self::STATUS_PENDING => $this->_helper()->__("Pending"),
            self::STATUS_APPROVED => $this->_helper()->__("Approved"),
            self::STATUS_REJECTED => $this->_helper()->__("Rejected"),
        );
    }

    public function toOptionArray()
    {
        $result = array();
        foreach ($this->getOptionsArray() as $value=>$label){
            $result[] = array('value'=>$value, 'label'=>$label);
        }
        return $result;
    }


    public function approve()
    {
        $this
            ->setSessionId(null)
            ->setStatus(self::STATUS_APPROVED)
            ->save();
        return $this;
    }

    public function reject()
    {
        $this
            ->setSessionId(null)
            ->setStatus(self::STATUS_REJECTED)
            ->save();
        return $this;
    }


    protected function _prepareComment($message)
    {
        $message = html_entity_decode($message);
        return strip_tags($message);
    }

    public function comment(array $data)
    {
        $this->addData($data);
        $this->setStoreId(Mage::app()->getStore()->getId());
        if ($this->_helper()->getCommentsAutoapprove()){
            $this->setStatus(self::STATUS_APPROVED);
            $this->setSessionId(null);
        } else {
            $this->setStatus(self::STATUS_PENDING);
        }
        $this->setMessage( $this->_prepareComment($data['message']) );
        $this->save();

        $this->_helper()->getNotifier()->notifyAboutPendingComment($this);

        return $this;
    }

    public function reply(array $data)
    {
        /** @var Magpleasure_Guestbook_Model_Message $message  */
        $message = Mage::getModel('guestbook/message');
        $message->setReplyTo($this->getId());
        $message->comment($data);
        return $message;
    }

}
	 