<?php
class Rack_SelfDelete_Helper_Data extends Mage_Core_Helper_Abstract {
    const SECRET_KEY_PARAM_NAME = 'key';
    
    public function getSecretKey($controller = null, $action = null)
    {
        $salt = uniqid();
        $request = Mage::app()->getRequest();
        $p = explode('/', trim($request->getOriginalPathInfo(), '/'));
        if (!$controller) {
            $controller = !empty($p[1]) ? $p[1] : $request->getControllerName();
        }
        if (!$action) {
            $action = !empty($p[2]) ? $p[2] : $request->getActionName();
        }

        $secret = $controller . $action . $salt ;
        return Mage::helper('core')->getHash($secret);
    }
}
