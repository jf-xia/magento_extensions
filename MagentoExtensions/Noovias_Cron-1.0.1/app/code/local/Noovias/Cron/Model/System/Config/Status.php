<?php
/**
 * Noovias_Cron_Model_System_Config_Status
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
class Noovias_Cron_Model_System_Config_Status
{
    protected $states = array();
    protected $statesShort = array();

    /**
     * Retrieve all states
     *
     * @return array
     */
    public function getStates()
    {
        if (count($this->states) == 0) {
            $this->_initStates();
        }

        return $this->states;
    }

    /**
     * Retrieve all states for the settings grid
     *
     * @return array
     */
    public function getStatesSettings()
    {
        $states_settings = array();
        //Translate State
        $states_settings[Noovias_Cron_Model_Schedule_Config::STATUS_ENABLED] = Mage::helper('noovias_cron')
                ->__(Noovias_Cron_Model_Schedule_Config::STATUS_ENABLED);
        $states_settings[Noovias_Cron_Model_Schedule_Config::STATUS_DISABLED] = Mage::helper('noovias_cron')
                ->__(Noovias_Cron_Model_Schedule_Config::STATUS_DISABLED);

        return $states_settings;
    }

    /**
     * Retrieve all states for the history grid
     *
     * @return array
     */
    public function getStatesHistory()
    {
        $states_history = array();
        // Translate State
        $states_history[Mage_Cron_Model_Schedule::STATUS_ERROR] = Mage::helper('noovias_cron')
                ->__(Mage_Cron_Model_Schedule::STATUS_ERROR);
        $states_history[Mage_Cron_Model_Schedule::STATUS_MISSED] = Mage::helper('noovias_cron')
                ->__(Mage_Cron_Model_Schedule::STATUS_MISSED);
        $states_history[Mage_Cron_Model_Schedule::STATUS_SUCCESS] = Mage::helper('noovias_cron')
                ->__(Mage_Cron_Model_Schedule::STATUS_SUCCESS);

        return $states_history;
    }

    /**
     * Retrieve all states for the schedule grid
     *
     * @return array
     */
    public function getStatesSchedule()
    {
        $states_schedule = array();
        // Translate State
        $states_schedule[Mage_Cron_Model_Schedule::STATUS_RUNNING] = Mage::helper('noovias_cron')
                ->__(Mage_Cron_Model_Schedule::STATUS_RUNNING);
        $states_schedule[Mage_Cron_Model_Schedule::STATUS_MISSED] = Mage::helper('noovias_cron')
                ->__(Mage_Cron_Model_Schedule::STATUS_MISSED);
        $states_schedule[Mage_Cron_Model_Schedule::STATUS_PENDING] = Mage::helper('noovias_cron')
                ->__(Mage_Cron_Model_Schedule::STATUS_PENDING);

        return $states_schedule;
    }


    /**
     * Retrieve all states with short Text
     *
     * @return array
     */
    public function getStatesShort()
    {
        if (count($this->statesShort) == 0) {
            $this->_initStates();
        }

        return $this->statesShort;
    }

    /**
     *
     */
    protected function _initStates()
    {
        $this->_addState(Mage_Cron_Model_Schedule::STATUS_ERROR);
        $this->_addState(Mage_Cron_Model_Schedule::STATUS_MISSED);
        $this->_addState(Mage_Cron_Model_Schedule::STATUS_PENDING);
        $this->_addState(Mage_Cron_Model_Schedule::STATUS_RUNNING);
        $this->_addState(Mage_Cron_Model_Schedule::STATUS_SUCCESS);
    }

    /**
     * @param string $state
     */
    protected function _addState($state)
    {
        // Translate State
        $this->states[$state] = Mage::helper('noovias_cron')->__($state);

        // Translate Short State Identifier
        $this->statesShort[$state] = Mage::helper('noovias_cron')->__('short_' . $state);
    }

    /**
     * @return Varien_Data_Form_Element_Select
     */
    public function toFormElementSelect()
    {
        $data = $this->getStates();
        array_unshift($data, '');
        $selectType = new Varien_Data_Form_Element_Select();
        $selectType->setName('status')
                ->setId('status')
                ->setForm(new Varien_Data_Form())
                ->addClass('required-entry')
                ->setValues($data);
        return $selectType;
    }

}