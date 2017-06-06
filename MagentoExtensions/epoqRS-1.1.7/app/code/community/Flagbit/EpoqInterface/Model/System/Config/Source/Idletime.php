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
* @version $Id: Abstract.php 5 2009-07-03 09:22:08Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Model_System_Config_Source_Idletime
{
    public function toOptionArray()
    {
        return array(
            5	=> Mage::helper('epoqinterface')->__('%d Minutes', 5),
            10	=> Mage::helper('epoqinterface')->__('%d Minutes', 10),
            20	=> Mage::helper('epoqinterface')->__('%d Minutes', 20),
            30	=> Mage::helper('epoqinterface')->__('%d Minutes', 30),
            60	=> Mage::helper('epoqinterface')->__('%d Hour', 1),
            120	=> Mage::helper('epoqinterface')->__('%d Hours', 2),

        );
    }
}