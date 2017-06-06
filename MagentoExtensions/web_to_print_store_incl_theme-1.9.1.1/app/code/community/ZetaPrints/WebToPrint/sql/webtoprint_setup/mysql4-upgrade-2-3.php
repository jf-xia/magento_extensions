<?php

$profile_name = 'ZetaPrints templates synchronization';

$profile_model = Mage::getModel('dataflow/profile');

if ($profile_model->getResource()->isProfileExists($profile_name)) {
    $collection = $profile_model->getCollection();
    $collection->getSelect()->where('name = ?', $profile_name);

    if ($collection->count() == 1)
      $profile_model = $collection->getFirstItem();
}

$profile_model->setName($profile_name)
  ->setActionsXml("<action type=\"webtoprint/templates-synchronization\" method=\"parse\" />\n<action type=\"webtoprint/products-updating\" method=\"map\" />")
  ->setGuiData(false)
  ->setDataTransfer('interactive')
  ->save();


$profile_name = 'ZetaPrints products creation';

$profile_model = Mage::getModel('dataflow/profile');

if ($profile_model->getResource()->isProfileExists($profile_name)) {
    $collection = $profile_model->getCollection();
    $collection->getSelect()->where('name = ?', $profile_name);

    if ($collection->count() == 1)
      $profile_model = $collection->getFirstItem();
}

$profile_model->setName($profile_name)
  ->setActionsXml('<action type="webtoprint/products-creation" method="map" />')
  ->setGuiData(false)
  ->setDataTransfer('interactive')
  ->save();

?>
