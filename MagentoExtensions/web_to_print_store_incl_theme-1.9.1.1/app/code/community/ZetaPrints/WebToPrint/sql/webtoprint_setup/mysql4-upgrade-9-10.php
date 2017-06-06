<?php

$profile_model = Mage::getModel('dataflow/profile');
$name = 'ZetaPrints products creation';

if ($profile_model->getResource()->isProfileExists($name)) {
  $collection = $profile_model->getCollection();
  $collection->getSelect()->where('name = ?', $name);

  if ($collection->count() == 1)
    $collection->getFirstItem()->delete();
}

$profiles = array(
  array('name' => 'ZetaPrints simple products creation',
        'xml' => '<action type="webtoprint/products-creation" method="map" ' .
                         'product-type="simple" />' ),
  array('name' => 'ZetaPrints virtual products creation',
        'xml' => '<action type="webtoprint/products-creation" method="map" ' .
                         'product-type="virtual" />' ) );

foreach ($profiles as $profile) {
  $profile_model = Mage::getModel('dataflow/profile');

  if ($profile_model->getResource()->isProfileExists($profile['name'])) {
    $collection = $profile_model->getCollection();
    $collection->getSelect()->where('name = ?', $profile['name']);

    if ($collection->count() == 1)
      $profile_model = $collection->getFirstItem();
  }

  $profile_model
    ->setName($profile['name'])
    ->setActionsXml($profile['xml'])
    ->setGuiData(false)
    ->setDataTransfer('interactive')
    ->save();
}

?>
