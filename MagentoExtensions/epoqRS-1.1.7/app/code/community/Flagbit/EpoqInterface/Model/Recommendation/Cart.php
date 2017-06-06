<?php 
/*                                                                       *
* This script is part of the epoq Recommendation Service project         *
*                                                                        *
* epoqinterface is free software; you can redistribute it and/or modify  *
* it under the terms of the GNU General Public License version 2 as      *
* published by the Free Software Foundation.                             *
*                                                                        *
* This script is distributed in the hope that it will be useful, but     *
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
* TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
* Public License for more details.                                       *
*                                                                        *
* @version $Id: Cart.php 666 2011-07-06 13:44:33Z rieker $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Model_Recommendation_Cart extends Flagbit_EpoqInterface_Model_Recommendation_Abstract {

    protected $_getRecommendationFor = 'Cart';

    protected function getParamsArray()
    {
        $params = parent::getParamsArray();

        $items = $this->getQuote()->getAllVisibleItems();

        /*@var $item Mage_Sales_Model_Quote_Item */
        foreach ($items as $key => $item) {
            if ($option = $item->getOptionByCode('simple_product')) {
                $product = $option->getProduct();
                $variables['variantOf'][$key] = $item->getProduct()->getId();
            } else {
                $product = $item->getProduct();            
            }

            $params['productId'][$key] = $product->getId();
            $params['quantity'][$key] = $item->getQty();
            $params['unitPrice'][$key] = $item->getPrice();
        }

        $parentData = parent::getData();
        if (!array_key_exists('action', $parentData)) {
            $params['updateCart'] = '';
        }

        return $params;
    }

    /**
     * get Quote
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function getQuote()
    {
        if ($this->_quote === null) {
            $this->_quote = Mage::getSingleton('checkout/cart')->getQuote();
        }
        return $this->_quote;
    }
}