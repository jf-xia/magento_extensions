<?php
 /*
 * Arcanum Dev AwardPoints
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to arcanumdev@wafunotamago.com so we can send you a copy immediately.
 *
 * @category   Magento Sale Extension
 * @package    AwardPoints
 * @copyright  Copyright (c) 2012 Arcanum Dev. Y.K. (http://arcanumdev.wafunotamago.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Arcanumdev_Awardpoints_Model_Mysql4_Stats extends Mage_Core_Model_Mysql4_Abstract{protected function _construct(){$this->_init('awardpoints/stats','awardpoints_account_id');}}