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
* @version $Id: Data.php 583 2010-11-26 10:08:21Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/
class Flagbit_EpoqInterface_Helper_Debug extends Mage_Core_Helper_Abstract
{
	const LOG_FILE_NAME = 'epoq.log';
	
	/**
	 * XML Config Path to Product Identifier Setting
	 * 
	 * @var string
	 */
    const XML_CONFIG_PATH_DEBUG_MODE = 'epoqinterface/config/debug';
	
	/**
	 * Debug Log to file var/log/epoq.log
	 * 
	 * @param $message
	 * @param $level
	 * @param $file
	 * @param $forceLog
	 */
	public function log($message)
	{

		if(Mage::getStoreConfig(self::XML_CONFIG_PATH_DEBUG_MODE)) {
			Mage::log($message, null, self::LOG_FILE_NAME, true);
		}
		return $this;
	}
}