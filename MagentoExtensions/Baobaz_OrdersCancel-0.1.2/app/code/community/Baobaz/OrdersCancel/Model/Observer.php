<?php
/*
 * Created on Jun 22, 2009
 *
 * author : Laurent Clouet
 *
 * www.baobaz.com
 *
*/

class Baobaz_OrdersCancel_Model_Observer {
    private $log_file;
    private $status_to_cancel;
    private $expiration_time;

    public function cancelOldOrders($schedule) {
        //configuration
        $this->log_file = Mage::getStoreConfig('sales/orderscancel/log_file');
        $this->status_to_cancel = Mage::getStoreConfig('sales/orderscancel/status_to_cancel');
        $days = (int)Mage::getStoreConfig('sales/orderscancel/expiration_days');
        //$this->status_to_cancel = Mage::getStoreConfig('orderscancel/ordersettings/status_to_cancel');
        //$days = (int)Mage::getStoreConfig('orderscancel/timesettings/days');
        $hours = (int)Mage::getStoreConfig('sales/orderscancel/expiration_hours');
        $minutes = (int)Mage::getStoreConfig('sales/orderscancel/expiration_minutes');
        $this->expiration_time = (($days * 24 + $hours) * 60 + $minutes) * 60;

        $status = explode(',', $this->status_to_cancel);
       
        //getting all orders needed to be process
        $order_model = Mage::getModel('sales/order');
        $orders = $order_model->getCollection()
                ->addAttributeToFilter('status', array('in' => $status))
                ->addAttributeToFilter('created_at', array('lt' => date('Y-m-d H:i:s', time() - $this->expiration_time)));

        $nb_orders = count($orders);

        if($nb_orders > 0) {
            Mage::log('Beginning of cancelling '. $nb_orders .' old order(s)', null, $this->log_file);
            foreach ($orders as $order) {
                try{
                    $order = Mage::getModel('sales/order')->load($order->getId());
                    if ($order->canCancel()) {
                        $order->cancel();
                        //Adding comment for differentiating order automatically or manually canceled
                        $order->addStatusToHistory($order->getStatus(), 'Order automatically canceled because older than '. $days .' day(s) '. $hours .' hour(s) and '. $minutes .' minute(s)');
                        $order->save();
                        Mage::log('Order ' . $order->getRealOrderId() . ' canceled', null, $this->log_file);
                    }
                    else {
                        Mage::log('Order '.$order->getRealOrderId().' couldn\'t be canceled', null, $this->log_file);
                    }
                }
                catch(Exception $e){
                    $message = 'Error while canceling order ' . $order->getRealOrderId() . ': ' . $e->getCode() . '' . $e->getMessage();
                    Mage::log($message, null, $this->log_file);
                    continue;
                }
            }
            Mage::log('End of cancelling '. $nb_orders .' old order(s)', null, $this->log_file);
        }
    }
}


?>
