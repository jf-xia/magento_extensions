<?php

$profiles = array(
  array('name' => 'ZetaPrints catalogues creation',
        'xml' => '<action type="webtoprint/catalogues-creation" method="parse" />' ) );

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
