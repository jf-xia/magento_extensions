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

class MageWorx_CustomerCredit_Model_Code_Log extends Mage_Core_Model_Abstract
{
    const ACTION_TYPE_CREATED = 0;
    const ACTION_TYPE_UPDATED = 1;
    const ACTION_TYPE_USED    = 2;

    protected function _construct()
    {
        $this->_init('customercredit/code_log');
    }

    public function getActionTypesOptions()
    {
        return array(
            self::ACTION_TYPE_CREATED => Mage::helper('customercredit')->__('Created'),
            self::ACTION_TYPE_UPDATED => Mage::helper('customercredit')->__('Updated'),
            self::ACTION_TYPE_USED    => Mage::helper('customercredit')->__('Used'),
        );
    }

    protected function _beforeSave()
    {
        if (!$this->hasCodeModel())
            Mage::throwException(Mage::helper('customercredit')->__('Recharge code hasn\'t assigned.'));

        $this->setCodeId($this->getCodeModel()->getId());
        $this->setComment($this->_getComment());
        return parent::_beforeSave();
    }

    protected function _getComment()
    {
        $comment = '';
        switch ($this->getActionType())
        {
            case self::ACTION_TYPE_CREATED :
            case self::ACTION_TYPE_UPDATED :
                /*if ($user = Mage::getSingleton('admin/session')->getUser())
                {
                    $username = $user->getUsername();
                    if ($username) {
                        $comment =  Mage::helper('customercredit')->__('By Administrator %s', $username);
                    }
                }*/
                break;
            case self::ACTION_TYPE_USED :
                if ($customerId = $this->getCodeModel()->getCustomerId())
                {
                    $comment =  Mage::helper('customercredit')->__('By Customer #%s', $customerId);
                }
                break;
            default :
                Mage::throwException(Mage::helper('customercredit')->__('Unknown log action type.'));
                break;
        }
        return $comment;
    }
}