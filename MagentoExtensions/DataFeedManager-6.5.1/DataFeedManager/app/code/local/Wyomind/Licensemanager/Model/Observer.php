<?php

define('WS_URL', "http://www.wyomind.com/license_activation/?licensemanager=1.0.0&");

class Wyomind_Licensemanager_Model_Observer {

    private $_messages = array(
        'activation_key_warning' => "Your activation key is not yet registered.<br><a href='%s'>Go to system > configuration > Wyomind > %s</a>.",
        'license_code_warning' => "Your license is not yet activated.<br><a target='_blank' href='%s'>Please go to Wyomind license manager</a>",
        'ws_error' => "The Wyomind's license server encountered an error.<br><a target='_blank' href='%s'>Please go to Wyomind license manager</a>",
        'ws_no_allowed' => "Your server doesn't allow remote connections.<br><a target='_blank' href='%s'>Please go to Wyomind license manager</a>",
        'upgrade' => "<u>Extension upgrade from v%s to v%s</u>.<br> Your license must be updated.<br>Please clean all caches.",
        'license_warning' => "License Notification",
    );
    private $_refreshCache = false;

    function checkAllLicenses($observer) {

        $block = $observer->getEvent()->getBlock();
        $this->_block = $block;

        if (in_array(get_class($block), array('Mage_Adminhtml_Block_Page'))) {
            foreach ($this->getValues() as $ext)
                $this->checkActivation($ext);
        }
        if ($this->_refreshCache)
            Mage::getConfig()->cleanCache();
       
    }

    public function XML2Array($xml) {
        $newArray = array();
        $array = (array) $xml;
        foreach ($array as $key => $value) {
            $value = (array) $value;
            if (isset($value [0])) {
                $newArray [$key] = trim($value [0]);
            } else {
                $newArray [$key] = $this->XML2Array($value, true);
            }
        }
        return $newArray;
    }

    public function getValues() {
        $dir = "app/code/local/Wyomind/";
        $ret = array();
        if (is_dir($dir)) {
            if (($dh = opendir($dir)) != false) {
                while (($file = readdir($dh)) !== false) {
                    if (is_dir($dir . $file) && $file != "." && $file != ".." && $file!='Notificationmanager' ) {
                        if (is_file($dir . $file . '/etc/system.xml')) {
                            $xml = simplexml_load_file($dir . $file . '/etc/system.xml');
                            $namespace = strtolower($file);
                            $label = $this->XML2Array($xml);
                            $label = $label['sections'][$namespace]['label'];

                            $ret[] = array('label' => $label, 'value' => $file);
                        }
                    }
                }
                closedir($dh);
            }
        }
        return $ret;
    }

    protected function sprintf_array($format, $arr) {
        return call_user_func_array('sprintf', array_merge((array) $format, $arr));
    }

    protected function addWarning($name, $type, $vars=array(), $success=false) {

        if ($type)
            $output = $this->sprintf_array($this->_messages[$type], $vars);
        else
            $output = implode(' ' . $vars);
        $output = "<div style='border:1px dotted red; padding:2px;margin-bottom:10px;'> Wyomind " . $name .' :: '. $output . '</div>';

        if ($success)
           Mage::getSingleton('core/session')->addSucess($output);
        else
           Mage::getSingleton('core/session')->addError($output);
    }

    protected function checkActivation($extension) {

        $activation_key = Mage::getStoreConfig(strtolower($extension['value']) . "/license/activation_key");
        $licensing_method = Mage::getStoreConfig(strtolower($extension['value']) . "/license/get_online_license");
        $license_code = Mage::getStoreConfig(strtolower($extension['value']) . "/license/activation_code");
        $domain = Mage::getStoreConfig("web/secure/base_url");

        $registered_version = Mage::getStoreConfig(strtolower($extension['value']) . "/license/version");
        $current_version = Mage::getConfig()->getNode("modules/Wyomind_" . $extension['value'])->version;


        $ws_param = "&rv=" . $registered_version . "&cv=" . $current_version . "&activation_key=" . $activation_key . "&domain=" . $domain . "&store_code=" . Mage::app()->getStore()->getCode();

        // Extension upgrade
        if ($registered_version != $current_version && ($license_code || !empty($license_code))) {
            Mage::getConfig()->saveConfig(strtolower($extension['value']) . "/license/activation_code", "", "default", "0");
            $this->addWarning($extension['label'], 'upgrade', array($registered_version, $current_version));
        }
        // no activation key not yet registered
        elseif (!$activation_key) {

            Mage::getConfig()->saveConfig(strtolower($extension['value']) . "/license/activation_code", "", "default", "0");
            Mage::getConfig()->saveConfig(strtolower($extension['value']) . "/license/activation_code", "", "default", "0");
            $this->addWarning($extension['label'], 'activation_key_warning', array(Mage::helper("adminhtml")->getUrl("adminhtml/system_config/edit/section/" . strtolower($extension['value']) . "/"), strtolower($extension['label'])));
        }
        // not yet activated --> manual activation
        elseif ($activation_key && (!$license_code || empty($license_code)) && !$licensing_method) {
            Mage::getConfig()->saveConfig(strtolower($extension['value']) . "/license/activation_code", "", "default", "0");
            $this->addWarning($extension['label'], 'license_code_warning', array(WS_URL . 'method=post' . $ws_param));
        }
        // not yet activated --> automatic activation
        elseif ($activation_key && (!$license_code || empty($license_code)) && $licensing_method) {

            try {
                $ws = file_get_contents(WS_URL . 'method=get' . $ws_param);
                $ws_result = json_decode($ws);
                switch ($ws_result->status) {
                    case "success" :
                        $this->addWarning($extension['label'], false, array($ws_result->message), true);
                        Mage::getConfig()->saveConfig(strtolower($extension['value']) . "/license/version", $ws_result->version, "default", "0");
                        Mage::getConfig()->saveConfig(strtolower($extension['value']) . "/license/activation_code", $ws_result->activation, "default", "0");
                        $this->_refreshCache = true;
                        break;
                    case "error" :
                        $this->addWarning($extension['label'], false, array($ws_result->message));
                        Mage::getConfig()->saveConfig(strtolower($extension['value']) . "/license/activation_code", "", "default", "0");
                        $this->_refreshCache = true;
                        break;
                    default :
                        $this->addWarning($extension['label'], 'ws_error',array(WS_URL . 'method=post' . $ws_param));
                        Mage::getConfig()->saveConfig(strtolower($extension['value']) . "/license/activation_code", "", "default", "0");
                        $this->_refreshCache = true;
                        break;
                }
            } catch (Exception $e) {
                $this->addWarning($extension['label'], 'ws_no_allowed',array(WS_URL . 'method=post' . $ws_param));
                Mage::getConfig()->saveConfig(strtolower($extension['value']) . "/license/activation_code", "", "default", "0");
                $this->_refreshCache = true;
            }
        }
    }

}