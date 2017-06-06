<?php
/**
 * @category   ASPerience
 * @package    Asperience_Addresscomplete
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Addresscomplete_Block_Autocomplete extends Mage_Core_Block_Abstract
{
    protected $_suggestData = null;

	protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
    
	protected function _toHtml(){
    	$html = '';
        $suggestData = $this->getSuggestData();
        if (!($count = count($suggestData))) {
            return $html;
        }

        $count--;
        $html = '<ul><li style="display:none"></li>';
        foreach ($suggestData as $index => $item) {
            if ($index == 0) {
                $item['row_class'] .= ' first';
            }
            if ($index == $count) {
                $item['row_class'] .= ' last';
            }
            $html .=  "<li title=\"".$item['zip_code']."||".$item['city']."||".$item['region']."\" class=\"".$item['row_class']."\">"
                .$item['zip_code'].", <i>".$item['city']."</i></li>";
        }


        $html.= '</ul>';
        return $html;
    }
    
	public function getSuggestData(){
		if($this->getRequest()->getPost()){
			foreach($this->getRequest()->getPost() as $data){
					$postcode = is_array($data)?$data['postcode']:$data;
					break;
				}
			
	        if($postcode && $this->_getSession()->getCountryCityIsExist()){
				$idCountry = $idCountry = $this->_getSession()->getCountry();
	        	
		    	$collection = Mage::getResourceModel('addresscomplete/city_collection')
		    			->addZipCodeFilter($postcode, $idCountry);
		    	$counter = 0;
            	$data = array();
		        foreach ($collection as $city) {
		        	
		        	$region = Mage::getModel('addresscomplete/city')->getRegionName($idCountry, $city->getRegionCode());
		        	
			        $_data = array(
	                    'zip_code' => $city->getZipCode(),
			        	'city' => $city->getCity(),
			        	'region' => $region,
	                    'row_class' => (++$counter)%2?'odd':'even'
	                );
	                $data[] = $_data;
                	$this->_suggestData = $data;
		        }
				return $this->_suggestData;	
	        }
		}
    }
}