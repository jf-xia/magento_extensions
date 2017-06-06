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
 class Arcanumdev_Awardpoints_Block_Adminhtml_Stats extends Mage_Adminhtml_Block_Widget_Grid_Container{public function __construct(){$this->_controller='adminhtml_stats';$this->_blockGroup='awardpoints';$this->_headerText=Mage::helper('awardpoints')->__('Statistics');parent::__construct();$this->_addButtonLabel=Mage::helper('awardpoints')->__('Add Points');$this->_addButton('check_all_points',array('label'=>Mage::helper('awardpoints')->__('Refresh customer points'),'class'=>'save','onclick'=>'setLocation(\''.$this->getCheckPointsUrl().'\')'));}public function getCheckPointsUrl(){return $this->getUrl('*/*/checkpoints');}}