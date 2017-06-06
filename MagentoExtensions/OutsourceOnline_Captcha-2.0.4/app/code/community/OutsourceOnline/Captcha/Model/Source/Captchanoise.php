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
class OutsourceOnline_Captcha_Model_Source_Captchanoise
{
    public function toOptionArray()
    {
        return array(array('value' => '0', 'label' => '0'),
                     array('value' => '.1', 'label' => '.1'),
					 array('value' => '.2', 'label' => '.2'),
                     array('value' => '.3', 'label' => '.3')
                    );
    }
}
