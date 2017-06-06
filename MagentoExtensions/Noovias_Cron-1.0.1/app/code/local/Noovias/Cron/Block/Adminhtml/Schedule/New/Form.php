<?php
/**
 * Noovias_Cron_Block_Adminhtml_Schedule_New_Form
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
class Noovias_Cron_Block_Adminhtml_Schedule_New_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array('id' => 'new_form', 'action' => $this->getData('action'), 'method' => 'post'));
        $fieldset = $form->addFieldset('general', array('legend' => Mage::helper('noovias_cron')->__('Schedule Data')));

        // Job-Code
        $codeSelect = Mage::getSingleton('noovias_cron/system_config_code')->toFormElementSelect();

        $fieldset->addField('job_code', 'note', array(
            'label' => Mage::helper('noovias_cron')->__('Job Code'),
            'title' => Mage::helper('noovias_cron')->__('Job Code'),
            'class' => 'required-entry',
            'text' => $codeSelect->toHtml(),
        ));

        $fieldset->addField('status', 'note', array(
            'label' => Mage::helper('noovias_cron')->__('Status'),
            'title' => Mage::helper('noovias_cron')->__('Status'),
            'class' => 'required-entry',
            'text' => Mage::helper('noovias_cron')->__(Mage_Cron_Model_Schedule::STATUS_PENDING),
        ));

        $fieldset->addField('scheduled_at', 'date', array(
            'label' => Mage::helper('noovias_cron')->__('Scheduled At'),
            'title' => Mage::helper('noovias_cron')->__('Scheduled At'),
            'html_id' => 'scheduled_at',
            'name' => 'scheduled_at',
            'class' => 'required-entry',
            'format' => Varien_Date::DATETIME_INTERNAL_FORMAT, // hardcode because hardcoded values delimiter
            'time' => true,
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
        ));

        #$form->setValues($schedule->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}