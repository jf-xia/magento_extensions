<?php
/**
 * Copyright (C) 2011 MageMods.co
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class MageMods_OrderMonitor_Model_Cron
{
    const XML_PATH_CANCEL_PENDING = 'ordermonitor/cron/cancel_pending';
    const XML_PATH_CANCEL_AFTER   = 'ordermonitor/cron/cancel_after';
    const XML_PATH_CANCEL_STATUS  = 'ordermonitor/cron/cancel_status';

    public function run()
    {
        $stores = Mage::getModel('core/store')
            ->getCollection()
            ->addFieldToFilter('store_id', array('neq' => Mage_Core_Model_App::ADMIN_STORE_ID));

        foreach ($stores as $store) {
            if (!Mage::getStoreConfigFlag(self::XML_PATH_CANCEL_PENDING, $store)) {
                continue;
            }

            if (!intval(Mage::getStoreConfig(self::XML_PATH_CANCEL_AFTER, $store))) {
                continue;
            }

            $orders = Mage::getModel('sales/order')
                ->getCollection()
                ->addFieldToFilter('store_id', $store->getStoreId())
                ->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_PENDING_PAYMENT)
                ->addFieldToFilter('created_at', array('lt' => new Zend_Db_Expr("DATE_ADD('" . now() . "', INTERVAL -'" . intval(Mage::getStoreConfig(self::XML_PATH_CANCEL_AFTER, $store)) . "' MINUTE)")))
                ->setCurPage(1)
                ->setPageSize(10);

            foreach ($orders as $order) {
                if (!$order->canCancel() || $order->hasInvoices() || $order->hasShipments()) {
                    continue;
                }

                $order->cancel();

                if ($status = Mage::getStoreConfig(self::XML_PATH_CANCEL_STATUS, $store)) {
                    $order->addStatusHistoryComment('', $status)
                          ->setIsCustomerNotified(null);
                }

                $order->save();
            }
        }
    }
}
