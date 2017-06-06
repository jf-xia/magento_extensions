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
* @version $Id: Cart.php 5 2009-07-03 09:22:08Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Model_Order extends Flagbit_EpoqInterface_Model_Abstract {


	/**
	 * send Customer Data
	 *
	 * @param Mage_Sales_Model_Order $order
	 */
	public function send($order)
	{
	    $this->setAction('processCart');
        Mage::getSingleton('epoqinterface/recommendation_cart', $this->getData());	    
	}
	
    
}

