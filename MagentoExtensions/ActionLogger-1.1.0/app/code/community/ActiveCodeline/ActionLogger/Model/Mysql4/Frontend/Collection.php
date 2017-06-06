<?php
/**
 * ActiveCodeline_ActionLogger_Model_Model_Mysql4_Frontend_Collection
 *
 * @category    ActiveCodeline
 * @package     ActiveCodeline_Admin2
 * @author      Branko Ajzele (http://activecodeline.net)
 * @copyright   Copyright (c) Branko Ajzele
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ActiveCodeline_ActionLogger_Model_Mysql4_Frontend_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('activecodeline_actionlogger/frontend');
    }
}
