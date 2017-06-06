<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */
 
class MageWorx_CustomerCredit_Model_Credit_Log extends Mage_Core_Model_Abstract
{
    const ACTION_TYPE_UPDATED     = 0;
    const ACTION_TYPE_USED        = 1;
    const ACTION_TYPE_REFUNDED    = 2;
    const ACTION_TYPE_CREDITRULE  = 3;
    const ACTION_TYPE_CANCELED    = 4;
    
    protected function _construct()
    {
        $this->_init('customercredit/credit_log');
    }
    
    public function getActionTypesOptions()
    {
        return array(
            self::ACTION_TYPE_UPDATED     => Mage::helper('customercredit')->__('Modified'),
            self::ACTION_TYPE_USED        => Mage::helper('customercredit')->__('Used'),
            self::ACTION_TYPE_REFUNDED    => Mage::helper('customercredit')->__('Refunded'),
            self::ACTION_TYPE_CREDITRULE  => Mage::helper('customercredit')->__('Modified'),
            self::ACTION_TYPE_CANCELED  => Mage::helper('customercredit')->__('Canceled'),
        );
    }
    
    protected function _beforeSave()
    {
        if (!$this->hasCreditModel() || !$this->getCreditModel()->getId())
            Mage::throwException(Mage::helper('customercredit')->__('Customer credit hasn\'t assigned.'));

        $this->setCreditId($this->getCreditModel()->getId());
        $this->setComment($this->_getComment());
        return parent::_beforeSave();
    }
    
    protected function _getComment()
    {
        $comment = '';
        switch ($this->getActionType())
        {
            case self::ACTION_TYPE_UPDATED :
                if ($this->getCreditModel()->hasRechargeCode())
                {
                    $comment =  Mage::helper('customercredit')->__('By Recharge Code %s', $this->getCreditModel()->getRechargeCode());
                }
                elseif ($user = Mage::getSingleton('admin/session')->getUser()) 
                {                    
                    if (!$this->getCreditModel()->getComment())
                    {
                        //$comment =  Mage::helper('customercredit')->__('By Administrator %s', $username);
                    }
                    else 
                    {
                        $comment =  $this->getCreditModel()->getComment();
                    }

                }
                break;
            case self::ACTION_TYPE_USED :
                $this->_checkOrder();
                $comment =  Mage::helper('customercredit')->__('In Order #%s', $this->getCreditModel()->getOrder()->getIncrementId());
                break;
            case self::ACTION_TYPE_REFUNDED :
                $this->_checkCreditmemo();
                if ($this->getCreditModel()->getCreditRule()) {                
                    $comment =  Mage::helper('customercredit')->__("Credit Rule(s) Order #%s; \nCreditmemo #%s", $this->getCreditModel()->getOrder()->getIncrementId(), $this->getCreditModel()->getCreditmemo()->getIncrementId());
                    $this->getCreditModel()->setCreditRule(null);
                } else {
                    $comment =  Mage::helper('customercredit')->__("Order #%s; \nCreditmemo #%s", $this->getCreditModel()->getOrder()->getIncrementId(), $this->getCreditModel()->getCreditmemo()->getIncrementId());
                }    
                break;
            case self::ACTION_TYPE_CANCELED :
                //$this->_checkOrder();
                if ($this->getCreditModel()->getCreditRule()) {
                    $comment =  Mage::helper('customercredit')->__("Credit Rule(s) In Order #%s", $this->getCreditModel()->getOrder()->getIncrementId());
                    $this->getCreditModel()->setCreditRule(null);
                } else {
                    $comment =  Mage::helper('customercredit')->__("Order #%s", $this->getCreditModel()->getOrder()->getIncrementId());
                }    
                break;
            case self::ACTION_TYPE_CREDITRULE :
                $orderIncrementId = $this->getCreditModel()->getOrder()->getIncrementId();
            	if ($orderIncrementId>0) {                    
                    $comment = Mage::helper('customercredit')->__('Credit Rule "%s" In Order #%s', $this->getCreditModel()->getRuleName(), $orderIncrementId);
                } else {
                    $comment = Mage::helper('customercredit')->__('Credit Rule');
                }    
            	break;
            default :
                Mage::throwException(Mage::helper('customercredit')->__('Unknown log action type.'));
                break;
        }
        
        return $comment;
    }
    
    protected function _checkCreditmemo()
    {
        if (!$this->getCreditModel()->getCreditmemo() || !$this->getCreditModel()->getCreditmemo()->getIncrementId())
        {
            Mage::throwException(Mage::helper('customercredit')->__('Creditmemo not set.'));
        }
        $this->_checkOrder();
    }
    
    protected function _checkOrder()
    {
        if (!$this->getCreditModel()->getOrder() || !$this->getCreditModel()->getOrder()->getIncrementId())
        {
            Mage::throwException(Mage::helper('customercredit')->__('Order not set.'));
        }
    }    
    
    
}