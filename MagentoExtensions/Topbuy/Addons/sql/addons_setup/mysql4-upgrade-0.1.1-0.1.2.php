<?php
 
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
 
$configValuesMap = array(
  'addons/addons_email_warranty' =>
  'addons_email_warranty',
);
 
foreach ($configValuesMap as $configPath=>$configValue) {
    $installer->setConfigData($configPath, $configValue);
}