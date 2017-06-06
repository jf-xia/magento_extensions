<?php
 /**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2009 Maison du Logiciel (http://www.maisondulogiciel.com)
 * @author : Olivier ZIMMERMANN
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MDN_AdminLogger_Model_Mysql4_Log extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('AdminLogger/Log', 'al_id');
    }

    public function TruncateTable()
    {
    	$this->_getWriteAdapter()->delete($this->getMainTable(), "1=1");
    }
    
    public function Prune($delay)
    {
    	$limitTimeStamp = time() - $delay * 3600 * 24;
    	$limitDate = date('Y-m-d', $limitTimeStamp);
    	$this->_getWriteAdapter()->delete($this->getMainTable(), "al_date<'".$limitDate."'");
    }
    
}
?>