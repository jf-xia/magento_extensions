<?php
/**
 * Noovias_Cron_Block_Adminhtml_Settings_Edit
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
 * @copyright   Copyright (c) 2012 <info@noovias.com> - noovias.com
 * @license     <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link        http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Cron
 * @copyright      Copyright (c) 2012 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author      noovias.com - Core Team <info@noovias.com>
 */

class Noovias_Cron_Block_Adminhtml_Settings_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /** @var $factory Noovias_Cron_Model_Factory */
    protected $factory = null;

    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'noovias_cron';
        $this->_controller = 'adminhtml_settings';
        $this->_removeButton('reset');
        $this->_removeButton('delete');
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        /** @var $config Noovias_Cron_Model_Schedule_Config */
        $config = Mage::registry('noovias_cron_schedule_config');
        $jobCode = $config->getJobCode();
        return $this->helperCron()->__("Edit Item '%s'", $this->__($jobCode));
    }

    /**
     * @return Noovias_Cron_Helper_Data
     */
    public function helperCron()
    {
        return Mage::helper('noovias_cron');
    }

    /**
     * @param Noovias_Cron_Model_Factory $factory
     */
    public function setFactory(Noovias_Cron_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Noovias_Cron_Model_Factory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->helperCron()->getFactory();
        }
        return $this->factory;
    }

    /**
     * @return Noovias_Cron_Model_Schedule_Config
     */
    public function getScheduleConfig()
    {
        return Mage::registry('noovias_cron_schedule_config');
    }

    /**
     * @return string
     */
    public function getMinuteOption()
    {
        $dataObject = $this->getScheduleConfig()->getDataObject();
        if($dataObject !== null)
        {
            return $dataObject->getMinuteOption();
        }
        return '';
    }

    /**
     * @return string
     */
    public function getHourOption()
    {
        $dataObject = $this->getScheduleConfig()->getDataObject();
        if($dataObject !== null){
            return $dataObject->getHourOption();
        }
        return '';
    }

    /**
     * @return string
     */
    public function getDayOfMonthOption()
    {
        $dataObject = $this->getScheduleConfig()->getDataObject();
        if($dataObject !== null)
        {
            return $dataObject->getDayOfMonthOption();
        }
        return '';
    }

    /**
     * @return string
     */
    public function getMonthOption()
    {
        $dataObject = $this->getScheduleConfig()->getDataObject();
        if($dataObject !== null)
        {
            return $dataObject->getMonthOption();
        }
        return '';
    }

    /**
     * @return string
     */
    public function getDayOfWeekOption()
    {
        $dataObject = $this->getScheduleConfig()->getDataObject();
        if($dataObject !== null)
        {
            return $dataObject->getDayOfWeekOption();
        }
        return '';
    }
}