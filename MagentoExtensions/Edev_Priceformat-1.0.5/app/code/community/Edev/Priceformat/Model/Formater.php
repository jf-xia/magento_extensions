<?php class Edev_Priceformat_Model_Formater extends Mage_Directory_Model_Currency
{
    public function formatTxt($price, $options=array())
    {
        if (!is_numeric($price)) {
            $price = Mage::app()->getLocale()->getNumber($price);
        }

        $price = sprintf("%F", $price);
		
		$result = $options;

		if( Mage::getStoreConfig('priceformat/format/enabled') ){
			if( Mage::getSingleton('admin/session')->isLoggedIn() && Mage::getStoreConfig('priceformat/format/allow_admin') )
				$result = $this->getFormat($result,$price);
			elseif(!Mage::getSingleton('admin/session')->isLoggedIn())
				$result = $this->getFormat($result,$price);				
		}

        return Mage::app()->getLocale()->currency($this->getCode())->toCurrency($price, $result);
    }
	
	public function getFormat($result,$price)
	{
		$arr['display'] = '';
		
		switch(Mage::getStoreConfig('priceformat/format/use_long_name')){
			default:
			case "0": $symbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol(); break;
			case "1": $symbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getShortName(); break;
			case "2": $symbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getName(Mage::app()->getLocale()->getLocale()); break;

		}
		
		if(Mage::getStoreConfig('priceformat/format/wrap')){
			$symbol = "<span class='cur-symbol'>" . $symbol . "</span>";
		}
		
		if(Mage::getStoreConfig('priceformat/format/only_round_sum')){

			if(preg_match('/\.[0]{6}/',$price)){
				$arr['display'] = Mage::getStoreConfig('priceformat/format/price_postfix');

				if(Mage::getStoreConfig('priceformat/format/change_precision'))
					$arr['precision'] = 0;
			}
		}else{
			$arr['display'] = Mage::getStoreConfig('priceformat/format/price_postfix');
			if(Mage::getStoreConfig('priceformat/format/change_precision'))
				$arr['precision'] = 0;
		}
		
		if(Mage::getStoreConfig('priceformat/format/keep_symbol')){
			$arr['display'] = $arr['display'] . $symbol;
		}
		
		switch(Mage::getStoreConfig('priceformat/format/position')){
			case "0": $arr['position'] = Zend_Currency::RIGHT; break;
			default:
			case "1": $arr['position'] = Zend_Currency::LEFT; break;

		}
		return array_merge($result,$arr);
	}
}