<?php
/**
 * @category   ASPerience
 * @package    Asperience_Addresscomplete
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Addresscomplete_Model_Mysql4_City extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('addresscomplete/city', 'city_id');
    }
}