<?php
/**
 * Noovias_Cron_Model_Service_Schedule_InitCronExpression
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
class Noovias_Cron_Model_Service_Schedule_InitCronExpression extends Noovias_Cron_Model_Service_Abstract
{
    /** @var  $generationService Noovias_Cron_Service_GenerateCronExpression*/
    protected $generationService = null;

    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @return string
     * @throws Mage_Core_Exception
     */
    public function initFromRequest(Mage_Core_Controller_Request_Http $request)
    {
        if ($request->getParam('expert_mode') == 'on') {
            $cronExpression = $request->getParam('cron_expr');
            return $cronExpression;
        }

        try {
            // easy mapping
            $params = $request->getParams();
            $data = new Noovias_Cron_Data_CronExpression($params);

            //
            $cronExpression = $this->getGenerationService()->generateCronExprFromDataObject($data);

            return $cronExpression;
        }
        catch (Exception $e) {
            throw new Mage_Core_Exception($e->getMessage());
        }
    }

    /**
     * @param $generationService Noovias_Cron_Service_GenerateCronExpression
     */
    public function setGenerationService($generationService)
    {
        $this->generationService = $generationService;
    }

    /**
     * @return Noovias_Cron_Service_GenerateCronExpression
     */
    protected function getGenerationService()
    {
        if ($this->generationService === null) {
            $this->generationService = $this->getFactory()->getServiceGenerateCronExpr();
        }
        return $this->generationService;
    }
}