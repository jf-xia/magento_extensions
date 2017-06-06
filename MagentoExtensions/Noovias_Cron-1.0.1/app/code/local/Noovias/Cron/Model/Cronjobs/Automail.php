<?php
/**
 * Noovias_Cron_Model_Cronjobs_Automail
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
 * @copyright   Copyright (c) 2011 <info@noovias.com> - noovias.com
 * @license     <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link        http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Cron
 * @copyright      Copyright (c) 2011 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author      noovias.com - Core Team <info@noovias.com>
 */
class Noovias_Cron_Model_Cronjobs_Automail
{
    /** @var $factory Noovias_Cron_Model_Factory */
    protected $factory = null;
    /**
     * @return bool
     */
    public function checkFailedJobs()
    {
        if (!Mage::getStoreConfig('noovias_cron/email_error/enabled')) {
            return false;
        }

        /** @var $model Mage_Cron_Model_Schedule */
        $model = $this->getFactory()->getModelCronSchedule();
        $collection = $model->getCollection();

        /** @var $collection Mage_Cron_Model_Mysql4_Schedule_Collection */
        $collection->getSelect()
                ->joinLeft(array('table_alias' => 'noovias_cron_processedjob'), 'main_table.schedule_id = table_alias.schedule_id', array('table_alias.email_sent'));
        $collection->addFieldToFilter('status', Mage_Cron_Model_Schedule::STATUS_ERROR);
        $collection->addFieldToFilter('table_alias.email_sent', array('null' => true));

        foreach ($collection as $s) {
            $mailData = array('schedule' => $s);
            $this->sendMail('noovias_cron/email_error', $mailData);
            $this->markJobAsEmailSent($s->getScheduleId());
        } // end foreach
    } // end function checkFailedJobs()

    /**
     * @return bool
     */
    public function checkMissedJobs()
    {
        if (!Mage::getStoreConfig('noovias_cron/email_missed/enabled')) {
            return false;
        }

        /** @var $model Mage_Cron_Model_Schedule */
        $model = $this->getFactory()->getModelCronSchedule();
        $collection = $model->getCollection();

        /** @var $collection Mage_Cron_Model_Mysql4_Schedule_Collection */
        $collection->getSelect()
                ->joinLeft(array('table_alias' => 'noovias_cron_processedjob'), 'main_table.schedule_id = table_alias.schedule_id', array('table_alias.email_sent'));
        $collection->addFieldToFilter('table_alias.email_sent', array('null' => true));
        $collection->addFieldToFilter('status', Mage_Cron_Model_Schedule::STATUS_MISSED);

        foreach ($collection as $s) {
            $mailData = array('schedule' => $s);
            $this->sendMail('noovias_cron/email_missed', $mailData);
            $this->markJobAsEmailSent($s->getScheduleId());
        } // end foreach
    } // end function checkMissedJobs()

    /**
     * @return bool
     */
    public function checkHangingJobs()
    {
        if (!Mage::getStoreConfig('noovias_cron/email_hanging/enabled')) {
            return false;
        }

        /** @var $model Mage_Cron_Model_Schedule */
        $model = $this->getFactory()->getModelCronSchedule();

        /** @var $collection Mage_Cron_Model_Mysql4_Schedule_Collection */
        $collection = $model->getCollection();

        // Get time after which a cronjob is considered as 'hanging':
        $datetime_hanging = new DateTime(now());
        $datetime_modifier_hanging = '-' . (Mage::getStoreConfig('noovias_cron/general/time_hanging') * 60) . ' seconds';
        $datetime_hanging->modify($datetime_modifier_hanging);

        $collection->getSelect()
                ->joinLeft(array('table_alias' => 'noovias_cron_processedjob'), 'main_table.schedule_id = table_alias.schedule_id', array('table_alias.email_sent'));
        $collection->addFieldToFilter('table_alias.email_sent', array('null' => true));
        $collection->addFieldToFilter('status', Mage_Cron_Model_Schedule::STATUS_RUNNING);
        $collection->addFieldToFilter('executed_at', array('to' => $datetime_hanging->format('Y-m-d H:i:s'))); //

        foreach ($collection as $s) {
            $mailData = array('schedule' => $s);
            $this->sendMail('noovias_cron/email_hanging', $mailData);
            $this->markJobAsEmailSent($s->getScheduleId());
        } // end foreach
    }

    /**
     * @return bool
     */
    public function checkLongJobs()
    {
        if (!Mage::getStoreConfig('noovias_cron/email_long/enabled')) {
            return false;
        }

        $minutes_overlong = Mage::getStoreConfig('noovias_cron/general/time_long');

        /** @var $model Mage_Cron_Model_Schedule */
        $model = $this->getFactory()->getModelCronSchedule();

        /** @var $collection Mage_Cron_Model_Mysql4_Schedule_Collection */
        $collection = $model->getCollection();
        $collection->getSelect()
                ->joinLeft(array('table_alias' => 'noovias_cron_processedjob'), 'main_table.schedule_id = table_alias.schedule_id', array('table_alias.email_sent'));
        $collection->addFieldToFilter('table_alias.email_sent', array('null' => true));
        $collection->getSelect()
                ->where('TIMESTAMPDIFF(SECOND, executed_at, finished_at) > ' . (60 * $minutes_overlong));
        //$collection->getSelect()->where('TIMESTAMPDIFF(SECOND, executed_at, finished_at) > '.(15)); // DEBUG, find jobs longer than 15 seconds, these can be produced by adding sleep(16); to the beginning of this function

        foreach ($collection as $s) {
            $mailData = array('schedule' => $s);
            $this->sendMail('noovias_cron/email_long', $mailData);
            $this->markJobAsEmailSent($s->getScheduleId());
        } // end foreach

    }

    /**
     * Marks a cronjob as 'email_sent', to prevent multiple emails for the same cronjob.
     *
     * @static
     * @param long $scheduleId
     * @return void
     */
    private function markJobAsEmailSent($scheduleId)
    {
        /** @var $modelProcessed Noovias_Cron_Model_Processedjob */
        $modelProcessed = $this->getFactory()->getModelProcessedjob();
        $modelProcessed->setEmailSent('1');
        $modelProcessed->setScheduleId($scheduleId);
        $modelProcessed->save();
    }

    /**
     * @return Noovias_Cron_Helper_Data
     */
    protected function helperCron()
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
        if($this->factory === null)
        {
            $this->factory = $this->helperCron()->getFactory();
        }
        return $this->factory;
    }

    /**
     * @return Noovias_Extensions_Model_Factory
     */
    protected function getFactoryExtensions()
    {
        return Mage::getModel('noovias_extensions/factory');
    }

    protected function sendMail($pathConfig, $mailData)
    {
        $factoryExtensions = $this->getFactoryExtensions();
        $serviceEmailSend = $factoryExtensions->getServiceEmailSendByConfigPath($pathConfig);
        $serviceEmailSend->execute($mailData);
    }
}