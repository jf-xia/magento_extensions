<?php

/**
 * AlipayCrossborder Form Block
 *
 * @category   NeedTool
 * @package    NeedTool_AlipayCrossborder
 * @name       NeedTool_AlipayCrossborder_Block_Form
 * @author     NeedTool.com <cs@needtool.com>
 */
class NeedTool_AlipayCrossborder_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        $this->setTemplate('needtoolalipaycrossborder/form.phtml');
        parent::_construct();
    }

}