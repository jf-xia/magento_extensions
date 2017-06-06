<?php
 
class Wyomind_Notificationmanager_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{
    public function applyUpdates()
    {
        if (!Mage::isInstalled()) {
            $dir = "app/code/local/Wyomind/";
            $ret = array();
            if (is_dir($dir)) {
                if (($dh = opendir($dir)) != false) {
                    while (($file = readdir($dh)) !== false) {
                        if (is_dir($dir.$file) && $file != "." && $file != "..") {
                            $enabled = Mage::getConfig()->getModuleConfig('Wyomind_'.ucfirst($namespace = strtolower($file)))->is('active', 'true');
                            if ($enabled)
                                $ret[] = $file;
                        }
                    }
                    closedir($dh);
                }
            }
            Mage::getConfig()->saveConfig("notificationmanager/notificationmanager/extensions",implode(',',$ret), "default", "0");
            Mage::getConfig()->cleanCache();
        }
 
        return parent::applyUpdates();
    }
}