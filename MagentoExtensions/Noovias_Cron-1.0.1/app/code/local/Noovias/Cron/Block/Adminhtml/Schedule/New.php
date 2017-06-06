<?php
/**
 * Noovias_Cron_Block_Adminhtml_Schedule_New
 *
 * NOTICE OF LICENSE
 *
 * Noovias_Cron is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Noovias_Cron is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Noovias_Cron. If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Noovias_Cron to newer
 * versions in the future. If you wish to customize Noovias_Cron for your
 * needs please refer to http://www.noovias.com for more information.
 *
 * @category    Noovias
 * @package        Noovias_Cron
 * @copyright   Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license     <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link        http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Cron
 * @copyright      Copyright (c) 2010 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author      noovias.com - Core Team <info@noovias.com>
 */
class Noovias_Cron_Block_Adminhtml_Schedule_New extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     *
     */
    public function __construct()
    {
        $this->_blockGroup = 'noovias_cron';
        $this->_controller = 'adminhtml_schedule';

        parent::__construct();

    }

    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setChild('new_form',
            $this->getLayout()->createBlock('noovias_cron/adminhtml_schedule_new_form')
        );

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        /** @var $schedule Noovias_Cron_Model_Schedule */
        $schedule = Mage::registry('noovias_cron_schedule');
        if ($schedule && $schedule->getId()) {
            return Mage::helper('noovias_cron')->__("New Item");
        }
        else {
            return Mage::helper('noovias_cron')->__('Add Item');
        }

    }

    /**
     * @return string
     */
    public function getFormHtml()
    {
        return $this->getChildHtml('new_form');
    }
}