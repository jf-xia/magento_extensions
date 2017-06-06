<?php
/**
 * Noovias_Cron_Block_Adminhtml_Settings_Grid
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

class Noovias_Cron_Block_Adminhtml_Settings_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /** @var $factory Noovias_Cron_Model_Factory */
    protected $factory = null;

    public function __construct()
    {
        parent::__construct();
        $this->setId('cron_settings_grid');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
        /** set default limit: this amount will be displayed properly
         *
         * do this because there are no methods to completely turn off the pagination
         */
        $this->setDefaultLimit(1000);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        /** @var $collection Noovias_Cron_Model_Mysql4_Schedule_Config_Collection*/
        $collection = $this->getFactory()->getModelScheduleConfig()->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('job_code', array(
            'header' => $this->helperCron()->__('Code'),
            'width' => '200px',
            'type' => 'text',
            'index' => 'job_code',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('cron_expr', array(
            'header' => $this->helperCron()->__('Cron Expression'),
            'width' => '100px',
            'type' => 'text',
            'index' => 'cron_expr',
            'filter' => false,
            'sortable' => false,
            'renderer' => 'noovias_cron/adminhtml_widget_grid_column_renderer_cronExpression',
        ));

        $this->addColumn('status', array(
            'header' => $this->helperCron()->__('Status'),
            'width' => '10px',
            'type' => 'options',
            'index' => 'status',
            'filter' => false,
            'sortable' => false,
            'options' => Mage::getSingleton('noovias_cron/system_config_status')->getStatesSettings(),
            'renderer' => 'noovias_cron/adminhtml_widget_grid_column_renderer_statusConfig',
        ));

        $this->addColumn('created', array(
            'header' => $this->helperCron()->__('Created'),
            'index' => 'created',
            'type' => 'datetime',
            'width' => '80px',
            'renderer' => 'noovias_extensions/adminhtml_widget_grid_column_renderer_datetime',
        ));

        $this->addColumn('updated', array(
            'header' => $this->helperCron()->__('Updated'),
            'index' => 'updated',
            'type' => 'datetime',
            'width' => '80px',
            'renderer' => 'noovias_extensions/adminhtml_widget_grid_column_renderer_datetime',
        ));

        if (Mage::getSingleton('admin/session')->isAllowed('system/noovias_cron/settings/actions/edit')) {
            $this->addColumn('action',
                array(
                    'header' => $this->helperCron()->__('Action'),
                    'width' => '50px',
                    'type' => 'action',
                    'getter' => 'getJobCode',
                    'actions' => array(
                        array(
                            'caption' => $this->helperCron()->__('Edit'),
                            'url' => array('base' => '*/*/edit'),
                            'field' => 'job_code'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'index' => 'stores',
                    'is_system' => true,
                ));
        }

        return parent::_prepareColumns();
    }

    /**
     * @return Noovias_Cron_Block_Adminhtml_Settings_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('job_code');
        $this->getMassactionBlock()->setFormFieldName('cronjob_codes');
        $this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem('disable_cron', array(
            'label' => $this->helperCron()->__('Disable'),
            'url' => $this->getUrl('*/*/massDisable'),
        ));

        $this->getMassactionBlock()->addItem('enable_cron', array(
            'label' => $this->helperCron()->__('Enable'),
            'url' => $this->getUrl('*/*/massEnable'),
        ));

        return $this;
    }

    /**
     * @return Noovias_Cron_Block_Adminhtml_Settings_Grid
     */
    protected function _prepareGrid()
    {
        $this->_prepareColumns();
        $this->_prepareMassactionBlock();

        /**
         * Set checkbox column width
         */
        $massActionColumn = $this->_columns['massaction'];
        $massActionColumn->addData(array('width' => '5%'));

        $this->_prepareCollection();
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
     * @param $row
     * @return bool|string
     */
    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('system/noovias_cron/settings/actions/edit')) {
            return $this->getUrl('*/*/edit', array('job_code' => $row->getJobCode()));
        }
        return false;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * Overwrite parent method in order to use the job_code as MassActionIdField
     *
     * see Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Checkbox::render()
     *
     * @return Noovias_Cron_Block_Adminhtml_Settings_Grid
     */
    protected function _prepareMassactionColumn()
    {
        parent::_prepareMassactionColumn();
        $this->_columns['massaction']->addData(
            array('use_index' => true)
        );
        return $this;
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
}