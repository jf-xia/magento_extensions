<?php

/**
 * Alipay Form Block
 *
 * @category   NeedTool
 * @package    NeedTool_Alipay
 * @name       NeedTool_Alipay_Block_Form
 * @author     NeedTool.com <cs@needtool.com>
 */
class NeedTool_OfflineM1_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        $this->setTemplate('needtoolofflinepay/form.phtml');
        parent::_construct();
    }

}