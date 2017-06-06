<?php
/*
* @author Tatvic Interactive
* Email : info@liftsuggest.com
* URL : http://www.liftsuggest.com
* Description : LiftSuggest Recommendations is the module that helps you show recommendations for your products to users/visitors on product pages and/or shopping cart page. This will help in increasing the average order value and conversion rate of your site.
* File : Yesno.php
* @copyright Copyright (C) 20011 Tatvic Interactive - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see license.php
* LiftSuggest Recommendations is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
 * Used in creating options for Traditional|Asynchronous config value selection
 *
 */
class Tatvic_LiftSuggest_Model_System_Config_Source_Yesno
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Traditional')),
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Asynchronous')),
        );
    }

}
