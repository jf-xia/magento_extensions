<?php


class Moo_Catalog_Block_Product_View_Media extends Mage_Catalog_Block_Product_View_Media
{

    public function renderCloudOptions()
    {
        $output = "";
        $width = $this->getCloudConfig('zoomImage/zoomWidth');
        if (empty($width) || !is_numeric($width)) {
            $width = 'auto';
        }
        $height = $this->getCloudConfig('zoomImage/zoomHeight');
        if (empty($height) || !is_numeric($height)) {
            $height = 'auto';
        }
        $output .= "zoomWidth: '" . $width . "',";
        $output .= "zoomHeight: '" . $height . "',";
        $output .= "position: '" . $this->getCloudConfig('zoomImage/position') . "',";
        $output .= "smoothMove: " . (int) $this->getCloudConfig('zoomImage/smoothMove') . ",";
        $output .= "showTitle: " . ($this->getCloudConfig('zoomImage/showTitle') ? 'true' : 'false') . ",";
        $output .= "titleOpacity: " . (float) ($this->getCloudConfig('zoomImage/titleOpacity')/100) . ",";

        $adjustX = (int) $this->getCloudConfig('zoomImage/adjustX');
        $adjustY = (int) $this->getCloudConfig('zoomImage/adjustY');
        if ($adjustX > 0) {
            $output .= "adjustX: " . $adjustX . ",";
        }
        if ($adjustY > 0) {
            $output .= "adjustY: " . $adjustY . ",";
        }

        $output .= "lensOpacity: " . (float) ($this->getCloudConfig('lens/lensOpacity')/100) . ",";

        $tint = $this->getCloudConfig('originalImage/tint');
        if (!empty($tint)) {
            $output .= "tint: '" . $this->getCloudConfig('originalImage/tint') . "',";
        }
        $output .= "tintOpacity: " . (float) ($this->getCloudConfig('originalImage/tintOpacity')/100) . ",";
        $output .= "softFocus: " . ($this->getCloudConfig('originalImage/softFocus') ? 'true' : 'false') . "";

        return $output;
    }

    public function getCloudConfig($name)
    {
        return Mage::getStoreConfig('moo_cloudzoom/' . $name);
    }

    public function getCloudImage($product, $imageFile=null)
    {
        if ($imageFile !== null) {
            $imageFile = $imageFile->getFile();
        }
        $image = $this->helper('catalog/image')->init($product, 'image', $imageFile);
        
        
            
    $_product = $this->getProduct();
    $_helper = $this->helper('catalog/output');
    
    if(Mage::helper("ExtraConfig")->is_mobile() == true) {
            $layout = 'vertical';
    } else {
            $layout = Mage::helper("ExtraConfig")->themeOptions('productlayout');
            $lyt = $_product->getAttributeText('productlayout');
            if($lyt != null){ $layout = $lyt; }
    }
    
        if ($layout == null || $layout == 'default'){
        $width = 374;
        $height = 498; }
        elseif ($layout == 'horizontal'){
            $width = 500;
        $height = 498; }
        elseif ($layout == 'vertical'){
            $width = 300;
        $height = 498; }
        elseif ($layout == 'custom1'){
            $width = 901;
        $height = 498; }
        elseif ($layout == 'custom2'){
            
            $galleryImages = $this->getGalleryImages();
            if (count($galleryImages) > 1) {            
            $width = 374; }
            else {
               $width = 524; 
            }
        $height = 498; }
        
         /*$width = $this->getCloudConfig('originalImage/imageWidth');
        $height = $this->getCloudConfig('originalImage/imageHeight');*/
        
        if (!empty($width) && !empty($height)) {
            return $image->resize($width, $height);
        } else if (!empty($width)) {
            return $image->resize($width);
        } else if (!empty($height)) {
            return $image->resize($height);
        }
        
        return $image;
    }
}
