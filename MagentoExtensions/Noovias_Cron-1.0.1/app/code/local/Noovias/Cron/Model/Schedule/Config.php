<?php
/**
 * Noovias_Cron_Model_Schedule_Config
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
 *
 * @method int getId()
 * @method string getJobCode()
 * @method string getStatus()
 * @method string getCronExpr()
 * @method int getCreatedBy()
 * @method int getUpdatedBy()
 * @method string getCreated()
 * @method string getUpdated()
 *
 * @method setJobCode(string $jobCode)
 * @method setStatus(string $status)
 * @method setCronExpr(string $cronExpr)
 * @method setCreatedBy(int $adminUserId)
 * @method setUpdatedBy(int $adminUserId)
 * @method setCreated(string $datetime)
 * @method setUpdated(string $datetime)
 */

class Noovias_Cron_Model_Schedule_Config
    extends Mage_Core_Model_Abstract
{
    const STATUS_ENABLED = 'enabled';
    const STATUS_DISABLED = 'disabled';
    /**
     * @var $helperCron Noovias_Cron_Helper_Data
     */
    protected $helperCron = null;
    /**
     * @var $factory Noovias_Cron_Model_Factory
     */
    protected $factory = null;
    /**
     * @var $dataObject Noovias_Cron_Data_CronExpression
     */
    protected $dataObject = null;

    /** @var $generationService Noovias_Cron_Service_GenerateDataObject */
    protected $generationService = null;

    /** @var $validationService Noovias_Cron_Service_ValidateCronExpression */
    protected $validationService = null;

    /** @var  $initCronExpressionService Noovias_Cron_Model_Service_Schedule_InitCronExpression*/
    protected $initCronExpressionService = null;

    protected function _construct()
    {
        $this->_init('noovias_cron/schedule_config');
    }

    public function setCronExpr($cronExpr)
    {
        $this->setData('cron_expr', $cronExpr);
        $this->initDataObject();
    }

    /**
     * @return bool
     */
    public function isStatusEnabled()
    {
        return $this->getStatus() == self::STATUS_ENABLED;
    }

    /**
     * @return bool
     */
    public function isStatusDisabled()
    {
        return $this->getStatus() == self::STATUS_DISABLED;
    }

    public function setStatusEnabled()
    {
        $this->setStatus(self::STATUS_ENABLED);
    }

    public function setStatusDisabled()
    {
        $this->setStatus(self::STATUS_DISABLED);
    }

    protected function _beforeSave()
    {
        if (!$this->getId()) {
            $this->setCreated(now());
            $this->setCreatedBy($this->helperCron()->getCurrentAdminUserId());
        }
        else {
            $this->setUpdated(now());
            $this->setUpdatedBy($this->helperCron()->getCurrentAdminUserId());
        }
        return parent::_beforeSave();
    }

    /**
     * @return Noovias_Cron_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->helperCron()->getFactory();
        }
        return $this->factory;
    }

    /**
     * @param $request Mage_Core_Controller_Request_Http
     */
    public function initByRequest($request)
    {
        $this->initCronExprByRequest($request);
        $this->setJobCode($request->getParam('job_code'));
        $this->setStatus($request->getParam('status'));
    }

    /**
     * @param $request Mage_Core_Controller_Request_Http
     */
    protected function initCronExprByRequest($request)
    {
        $cronExpr = $this->getInitCronExpressionService()->initFromRequest($request);
        $this->setCronExpr($cronExpr);
    }

    protected function _afterLoad()
    {
        $this->initDataObject();
        return parent::_afterLoad();
    }

    /**
     *
     */
    protected function initDataObject()
    {
        try {
            $generationService = $this->getGenerationService();
            if ($this->isCronExpressionPresentable()) {
                $this->dataObject = $generationService->generateDataObjectFromCronExpr($this->getCronExpr());
            }
        }
        catch (Exception $e) {
            $this->dataObject = null;
        }
    }

    public function isDataObjectSet()
    {
        if ($this->dataObject === null) {
            return false;
        }
        return true;
    }

    /**
     *
     * @return Noovias_Cron_Data_CronExpression
     */
    public function getDataObject()
    {
        return $this->dataObject;
    }

    /**
     * @param Noovias_Cron_Data_CronExpression $dataObject
     */
    public function setDataObject(Noovias_Cron_Data_CronExpression $dataObject)
    {
        $this->dataObject = $dataObject;
    }

    /**
     * @return Noovias_Cron_Helper_Data
     */
    protected function helperCron()
    {
        if ($this->helperCron === null) {
            $this->helperCron = Mage::helper('noovias_cron');
        }
        return $this->helperCron;
    }

    /**
     * @param Noovias_Cron_Helper_Data $helperCron
     */
    public function setHelperCron(Noovias_Cron_Helper_Data $helperCron)
    {
        $this->helperCron = $helperCron;
    }

    /**
     * @param $factory Noovias_Cron_Model_Factory
     */
    public function setFactory(Noovias_Cron_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Noovias_Cron_Service_GenerateDataObject $generationService
     */
    public function setGenerationService(Noovias_Cron_Service_GenerateDataObject $generationService)
    {
        $this->generationService = $generationService;
    }

    /**
     * @return Noovias_Cron_Service_GenerateDataObject
     */
    public function getGenerationService()
    {
        if($this->generationService === null){
            $this->generationService = $this->getFactory()->getServiceGenerateDataObject();
        }
        return $this->generationService;
    }

    /**
     * @param Noovias_Cron_Service_ValidateCronExpression $validationService
     */
    public function setValidationService(Noovias_Cron_Service_ValidateCronExpression $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * @return Noovias_Cron_Service_ValidateCronExpression
     */
    protected function getValidationService()
    {
        if($this->validationService === null){
            $this->validationService = $this->getFactory()->getServiceValidateCronExpr();
        }
        return $this->validationService;
    }

    /**
     * @param Noovias_Cron_Model_Service_Schedule_InitCronExpression $initCronExpressionService
     */
    public function setInitCronExpressionService(Noovias_Cron_Model_Service_Schedule_InitCronExpression $initCronExpressionService)
    {
        $this->initCronExpressionService = $initCronExpressionService;
    }

    /**
     * @return Noovias_Cron_Model_Service_Schedule_InitCronExpression
     */
    protected function getInitCronExpressionService()
    {
        if($this->initCronExpressionService === null){
            $this->initCronExpressionService = $this->getFactory()->getServiceInitCronExpression();
        }
        return $this->initCronExpressionService;
    }

    /**
     * @return array
     */
    public function asDataArray()
    {
        $dataArray = array();

        try {
            if ($this->isDataObjectSet()) {
                $reflect = new ReflectionClass(($this->getDataObject()));
                $properties = $reflect->getProperties();
                foreach ($properties as $property) {
                    $fieldName = $property->getName();
                    $value = $this->getDataObject()->get($fieldName);
                    if ($value !== null) {
                        $dataArray[$fieldName] = $value;
                    }
                }
            }
        } catch (Exception $e) {
            return array();
        }
        return $dataArray;
    }

    /**
     * Validate whether the cron expression is suitable to show in the edit form drop downs
     *
     * @return bool
     */
    public function isCronExpressionPresentable()
    {
        return $this->getValidationService()->isCronExpressionPresentable($this->getCronExpr());
    }
}