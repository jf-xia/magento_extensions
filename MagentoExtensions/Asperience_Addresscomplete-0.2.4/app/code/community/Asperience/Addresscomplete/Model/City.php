<?php
/**
 * @category   ASPerience
 * @package    Asperience_Addresscomplete
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Addresscomplete_Model_City extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('addresscomplete/city');
    }
    
	public function getRegionName($countryId, $region)
    {
    	$resource = Mage::getSingleton('core/resource');
		$read = $resource->getConnection('core_read');
		$regionTable = $resource->getTableName('directory/country_region');
		$select = $read->select()
			->from($regionTable, 'region_id')
            ->where('country_id=?', $countryId)
            ->where('code=?', $region);
        return $read->fetchOne($select);
    }
    
	public function getCountryCityIsExist($countryId)
    {
    	$resource = Mage::getSingleton('core/resource');
		$read = $resource->getConnection('core_read');
		$regionTable = $resource->getTableName('addresscomplete/city');
		$select = $read->select()
			->from($regionTable, 'country_id')
            ->where('country_id=?', $countryId);
        return $read->fetchOne($select)?true:false;
    }
}