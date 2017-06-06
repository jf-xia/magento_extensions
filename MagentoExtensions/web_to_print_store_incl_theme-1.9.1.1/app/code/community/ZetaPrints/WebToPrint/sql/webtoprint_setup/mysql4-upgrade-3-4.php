<?php

require_once Mage::getBaseDir() . '/lib/ZetaPrints/zetaprints-api.php';

$dir_name = zetaprints_generate_guid();

Mage::getConfig()->saveConfig('zetaprints/webtoprint/uploading/dir', $dir_name);
mkdir(Mage::getModel('catalog/product_media_config')->getTmpMediaPath($dir_name), 0777, true);

?>
