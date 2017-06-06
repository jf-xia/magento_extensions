<?php
/**
 * Noovias_Extensions_Helper_Breadcrumb
 *
 * NOTICE OF LICENSE
 *
 * Noovias_Extensions is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Noovias_Extensions is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Noovias_Extensions. If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Noovias_Extensions to newer
 * versions in the future. If you wish to customize Noovias_Extensions for your
 * needs please refer to http://www.noovias.com for more information.
 *
 * @category    Noovias
 * @package     Noovias_Extensions
 * @copyright   Copyright (c) 2012 <info@noovias.com> - noovias.com
 * @license     <http://www.gnu.org/licenses/>
 *                 GNU General Public License (GPL 3)
 * @link        http://www.noovias.com
 */

/**
 * @category       Noovias
 * @package        Noovias_Extensions
 * @copyright      Copyright (c) 2012 <info@noovias.com> - noovias.com
 * @license        http://opensource.org/licenses/osl-3.0.php
 *                 Open Software License (OSL 3.0)
 * @author      noovias.com - Core Team <info@noovias.com>
 */
class Noovias_Extensions_Helper_Breadcrumb extends Mage_Core_Helper_Abstract
{
    public function addByUrl()
    {
        /** @var $layout Mage_Core_Model_Layout */
        $layout = Mage::app()->getLayout();
        /** @var $breadcrumbs Mage_Page_Block_Html_Breadcrumbs */
        $breadcrumbs = $layout->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb(
            'home',
            array(
                'label' => $this->__('home'),
                'title' => $this->__('home'),
                'link' => $this->helperCoreUrl()->getHomeUrl(),
            )
        );

        /** @var $request Mage_Core_Controller_Request_Http */
        $request = Mage::app()->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $fullAction = $module . '_' . $controller . '_' . $action;

        if ($fullAction == 'cms_index_index') {
            return;
        }

        $breadcrumbs->addCrumb(
            $fullAction,
            array(
                'label' => $this->__($fullAction),
                'title' => $this->__($fullAction)
            )
        );
    }

    /**
     * @return Mage_Core_Helper_Url
     */
    protected function helperCoreUrl()
    {
        return Mage::helper('core/url');
    }

    public function getParameters($label, $title, $link = '', $first = '', $last = '', $readonly = '')
    {
        $args = array(
            'label' => $label,
            'title' => $title,
            'first' => $first,
            'last' => $last,
            'readonly' => $readonly,
        );

        $url = $link;
        if ($link != '') {
            if (is_array($link) and array_key_exists('@', $link)) {
                $attributes = $link['@'];
                unset($link['@']);
                if (is_array($attributes) and array_key_exists('helper', $attributes)) {
                    $url = $this->callHelper($attributes['helper'], $link);
                }
                if (is_array($attributes) and array_key_exists('model', $attributes)) {
                    $url = $this->callModel($attributes['model'], $link);
                }
            }
        }
        $args['link'] = $url;

        return $args;
    }

    protected function callHelper($helperString, $params)
    {
        $helperName = explode('/', (string)$helperString);
        $helperMethod = array_pop($helperName);
        $helperName = implode('/', $helperName);
        return call_user_func_array(array(Mage::helper($helperName), $helperMethod), $params);
    }

    protected function callModel($helperString, $params)
    {
        $helperName = explode('/', (string)$helperString);
        $helperMethod = array_pop($helperName);
        $helperName = implode('/', $helperName);
        return call_user_func_array(array(Mage::getModel($helperName), $helperMethod), $params);
    }

}