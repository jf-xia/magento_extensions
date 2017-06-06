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
 class Arcanumdev_Awardpoints_Block_Arcanumhead extends Mage_Core_Block_Template{protected function _prepareLayout(){parent::_prepareLayout();if(Mage::getStoreConfig('awardpoints/registration/referral_addthis',Mage::app()->getStore()->getId())&&Mage::getStoreConfig('awardpoints/registration/referral_addthis_account',Mage::app()->getStore()->getId())!=""){$block=$this->getLayout()->createBlock('Mage_Core_Block_Text');$block->setText('<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username="'.Mage::getStoreConfig('awardpoints/registration/referral_addthis_account', Mage::app()->getStore()->getId()).'></script>');$this->getLayout()->getBlock('head')->append($block);}}}