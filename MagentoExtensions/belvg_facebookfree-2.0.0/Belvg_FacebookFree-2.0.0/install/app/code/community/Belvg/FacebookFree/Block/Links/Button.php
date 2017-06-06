<?php

class Belvg_FacebookFree_Block_Links_Button extends Mage_Core_Block_Template
{

    /**
     * Get image for the connect button
     *
     * @return string
     */
    public function getImage()
    {
        $img_button = $this->getDefaultImage();

        if ($image = $this->helper('facebookfree')->getImageLogin()) {
            $img_button_path = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . 'facebookfree' . DS . $image;

            // if configured image was foud - use it
            if (file_exists($img_button_path)) {
                $img_button = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'facebookfree/' . $image;
            }
        }

        return $img_button;
    }

    /**
     * Get default image button
     *
     * @return string
     */
    public function getDefaultImage()
    {
        return $this->getSkinUrl($this->helper('facebookfree')->getDefaultImageLogin());
    }

}