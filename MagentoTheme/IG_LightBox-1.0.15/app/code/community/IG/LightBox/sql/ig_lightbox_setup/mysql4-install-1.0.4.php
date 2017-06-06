<?php
$installer = $this;
$installer->startSetup();

$installer->setConfigData('ig_lightbox/general/enabled',			1);
$installer->setConfigData('ig_lightbox/general/bigImageSize',		'800x600');
$installer->setConfigData('ig_lightbox/general/mainImageSize',		'265x265');
$installer->setConfigData('ig_lightbox/general/thumbnailImageSize',	'60x60');

$installer->setConfigData('ig_lightbox/display/backgroundOpacity',	'0.8');
$installer->setConfigData('ig_lightbox/display/backgroundColor',	'#000000');
$installer->setConfigData('ig_lightbox/display/imageboxOpacity',	'1.0');
$installer->setConfigData('ig_lightbox/display/imageboxColor',		'#000000');
$installer->setConfigData('ig_lightbox/display/toolbarOpacity',		'0.7');
$installer->setConfigData('ig_lightbox/display/toolbarColor',		'#000000');
$installer->setConfigData('ig_lightbox/display/toolbarTextColor',	'#ffffff');
$installer->setConfigData('ig_lightbox/display/toolbarTextSize',	'12');
$installer->setConfigData('ig_lightbox/display/toolbarTextFont',	'Verdana');
$installer->setConfigData('ig_lightbox/display/paddingSize',		'10');
$installer->setConfigData('ig_lightbox/display/borderSize',			'1');
$installer->setConfigData('ig_lightbox/display/borderColor',		'#909090');

$installer->setConfigData('ig_lightbox/effects/fadeIn',				'1.0');
$installer->setConfigData('ig_lightbox/effects/fadeOut',			'1.0');
$installer->setConfigData('ig_lightbox/effects/imageResize',		'0.5');
$installer->setConfigData('ig_lightbox/effects/imageSwap',			'0.5');

$installer->endSetup();
?>