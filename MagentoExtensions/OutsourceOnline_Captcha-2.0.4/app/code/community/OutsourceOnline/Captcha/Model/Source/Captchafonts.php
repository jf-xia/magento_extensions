<?php
/**
 * Outsource Online Captcha Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Outsource Online
 * @package    OutsourceOnline_Captcha
 * @author     Sreekanth Dayanand
 * @copyright  Copyright (c) 2010 Outsource Online. (http://www.outsource-online.net)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class OutsourceOnline_Captcha_Model_Source_Captchafonts
{
    public function toOptionArray()
    {
		$ttfPath = realpath(dirname(__FILE__)."/../../Helper/osolCaptcha/ttfs")."/";
		$ttfsAvailable = array();
		if ($handle = opendir($ttfPath)) {
			
		
			
			while (false !== ($entry = readdir($handle))) {
				if(preg_match("@.*\.(ttf|otf)@i",$entry))
				{
					
					$ttfsAvailable[] = array('value' => $entry, 'label'  => $entry);	
				}
			}
		
			
		
			closedir($handle);
		}
        return $ttfsAvailable;
    }
}
