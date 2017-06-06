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
* @version $Id: Head.php 248 2010-03-11 09:52:13Z weller $
* @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
*/

class Flagbit_EpoqInterface_Block_Head extends Mage_Core_Block_Abstract
{
	/**
	 * Returns the tracking code part one
	 * 
	 * @return string
	 */
    protected function _toHtml()
    {
    	$html = '';
    	
    	if (!$this->_beforeToHtml()) {
    		return $html;
    	}

    	$html = '<script type="text/javascript">' . "\n";
    	$html .= '//<![CDATA[' . "\n";
    	$html .= 'var eqJsHost = (("https:" == document.location.protocol) ? "https://" : "http://");' . "\n";
    	$html .= 'document.write(unescape("%3Cscript src=\'" + eqJsHost + "rs.epoq.de/web-api/epoq.js\' type=\'text/javascript\'%3E%3C/script%3E"));' . "\n";
		$html .= '//]]>' . "\n";
		$html .= '</script>' . "\n";
    	
        return $html;
    }
}
