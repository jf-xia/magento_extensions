<?php
/**
 * @category   ASPerience
 * @package    Asperience_Addresscomplete
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Addresscomplete_Model_Mysql4_City_Collection extends Varien_Data_Collection_Db
{

	const MAX_LIGNE  = 8;
	
	private function _getResource(){
		return Mage::getSingleton('core/resource');
	}
	
    public function __construct()
    {
        parent::__construct(Mage::getSingleton('core/resource')->getConnection('directory_read'));
        
        $resource = $this->_getResource();
        $this->_cityTable = $resource->getTableName('addresscomplete/city');
        
        $this->_select->from(array('c' => $this->_cityTable), 
        	array('region_code'=>'c.region_code', 
        	'zip_code'=>'c.zip_code', 'city' =>'c.default_name'));
        
        $this->setItemObjectClass(Mage::getConfig()->getModelClassName('addresscomplete/city'));
    }
    

	public function addZipCodeFilter($zip_code, $country_id)
    {
    	 if (!empty($zip_code) && !empty($country_id)) {
                $this->getSelect()
                ->where('zip_code LIKE ?',$zip_code.'%')
                ->where('country_id =  ?' , $country_id)
                ->order('zip_code', 'ASC')
                ->group(array('zip_code', 'default_name'))
                ->limit(self::MAX_LIGNE);
        }
        
        return $this;
    }
    
    
}