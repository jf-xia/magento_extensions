<?php
/**
 * Noovias_Cron_Model_Mysql4_Schedule_Config_Collection
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
class Noovias_Cron_Model_Mysql4_Schedule_Config_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * @var $serviceInitializeConfig Noovias_Cron_Model_Service_Schedule_InitializeConfig
     */
    protected $serviceInitializeConfig = null;
    /** @var $factory Noovias_Cron_Model_Factory */
    protected $factory = null;

    protected function _construct()
    {
        parent::_construct();

        $this->_init('noovias_cron/schedule_config');
    }


    /**
     * @return Noovias_Cron_Model_Mysql4_Schedule_Config_Collection
     *
     *
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        /**
         * Add availible cron jobs to collection, if they are not saved in the database, but
         * don't save the collection, new db entries are creted if a cron job is edited and saved
         * via the Backend Grid (Noovias - Cronjobs -> Settings)
         */
        $availibleJobCodes = $this->helperCron()->getAvailableJobCodesAsArray();
        foreach($availibleJobCodes as $jobCode){
            if(!$this->getItemByColumnValue('job_code', $jobCode)){
                /** @var $itemToAdd Noovias_Cron_Model_Schedule_Config */
                $itemToAdd = $this->getServiceInitializeConfig()->initByJobCode($jobCode, false);
                $this->addItem($itemToAdd);
            }
        }
        /**
         * Set the _totalRecords variable in order to render the cron jobs in the
         * grid (Noovias - Cronjobs -> Settings) (renders only if the getSize method
         * returns a value > 0)
         */
        $this->_totalRecords = count($this->getItems());

        /** Sort ascending by job_code */
        $items = $this->getItems();
        usort($items, array('Noovias_Cron_Model_Mysql4_Schedule_Config_Collection', 'sortByJobCode'));
        $this->_items = $items;
        return $this;
    }

    /**
     * @return Noovias_Cron_Helper_Data
     */
    protected function helperCron()
    {
        return Mage::helper('noovias_cron');
    }

    /**
     * @param $a Noovias_Cron_Model_Schedule_Config
     * @param $b Noovias_Cron_Model_Schedule_Config
     * @return int
     */
    static protected function sortByJobCode($a, $b)
    {
        if($a->getJobCode() == $b->getJobCode()){
            return 0;
        }
        return($a->getJobCode() < $b->getJobCode()) ? -1 : 1;
    }

    /**
     * @param Noovias_Cron_Model_Service_Schedule_InitializeConfig $serviceInitializeConfig
     */
    public function setServiceInitializeConfig($serviceInitializeConfig)
    {
        $this->serviceInitializeConfig = $serviceInitializeConfig;
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_InitializeConfig
     */
    protected function getServiceInitializeConfig()
    {
        if($this->serviceInitializeConfig === null){
            $this->serviceInitializeConfig = $this->getFactory()->getServiceInitializeConfig();
        }
        return $this->serviceInitializeConfig;
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
        if($this->factory === null){
            $this->factory = $this->helperCron()->getFactory();
        }
        return $this->factory;
    }
}