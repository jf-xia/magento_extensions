<?php
/**
 * DO NOT REMOVE OR MODIFY THIS NOTICE
 * 
 * EasyBanner module for Magento - flexible banner management
 * 
 * @author Templates-Master Team <www.templates-master.com>
 */

class TM_EasyCatalogImg_Helper_Image extends Mage_Core_Helper_Abstract
{
    public function resize($imageUrl, $width, $height)
    {
		// create folder
		if(!file_exists(Mage::getBaseDir('media').DS."catalog".DS."category".DS."resized")){
			mkdir(Mage::getBaseDir('media').DS."catalog".DS."category".DS."resized",0777);
		};

		$imageName = substr(strrchr($imageUrl,"/"),1);

		$imageResized = Mage::getBaseDir('media').DS."catalog".DS."category".DS."resized".DS.$imageName;

		$dirImg = Mage::getBaseDir().str_replace("/",DS,strstr($imageUrl,'/media'));

		 if (!file_exists($imageResized)&& file_exists($dirImg)) :
			$imageObj = new Varien_Image($dirImg);
			$imageObj->constrainOnly(TRUE);
			$imageObj->keepAspectRatio(TRUE);
			$imageObj->keepFrame(FALSE);
			$imageObj->resize(200,200 );
			$imageObj->save($imageResized);
		 endif;

		$imageUrl = Mage::getBaseUrl('media')."catalog/category/resized/".$imageName;
		
		return $imageUrl;
    }
}
