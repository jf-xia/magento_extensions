<?php

class Belvg_FacebookFree_Helper_Config extends Mage_Core_Helper_Data
{

    const XML_PATH_FBFREE_ENABLED = 'facebookfree/settings/enabled';
    const XML_PATH_FBFREE_APP_ID = 'facebookfree/settings/appid';
    const XML_PATH_FBFREE_APP_SECRET = 'facebookfree/settings/secret';
    const XML_PATH_FBFREE_CONNECT_IMAGE = 'facebookfree/settings/imglogin';
    const XML_PATH_FBFREE_CONNECT_DEFIMAGE = 'facebookfree/settings/defimage';
    const XML_PATH_FBFREE_LIKE_ENABLED = 'facebookfree/like/enabled';
    const XML_PATH_FBFREE_LIKE_LAYOUT = 'facebookfree/like/layout';
    const XML_PATH_FBFREE_LIKE_FACES = 'facebookfree/like/faces';
    const XML_PATH_FBFREE_LIKE_WIDTH = 'facebookfree/like/width';
    const XML_PATH_FBFREE_LIKE_COLOR = 'facebookfree/like/color';

    /**
     * Check if module is enabled
     *
     * @param mixed $store
     * @return boolen
     */
    public function isActive($store = '')
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_FBFREE_ENABLED, $store);
    }

    /**
     * Get application ID (see https://developers.facebook.com/apps/)
     *
     * @param mixed $store
     * @return string
     */
    public function getAppId($store = '')
    {
        return Mage::getStoreConfig(self::XML_PATH_FBFREE_APP_ID, $store);
    }

    /**
     * Get application secret key
     *
     * @param mixed $store
     * @return string
     */
    public function getAppSecret($store = '')
    {
        return Mage::getStoreConfig(self::XML_PATH_FBFREE_APP_SECRET, $store);
    }

    /**
     * Check if like option is enabled
     *
     * @param mixed $store
     * @return boolen
     */
    public function isActiveLike($store = '')
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_FBFREE_LIKE_ENABLED, $store);
    }

    /**
     * Get like button layout
     *
     * @return string
     */
    public function getLikeLayout()
    {
        return Mage::getStoreConfig(self::XML_PATH_FBFREE_LIKE_LAYOUT);
    }

    /**
     * Show profile pictures below the button.
     *
     * @return string (fb:like accepts true/false param)
     */
    public function isFacesLikeActive()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_FBFREE_LIKE_FACES) ? 'true' : 'false';
    }

    /**
     * The width of the plugin, in pixels.
     *
     * @return int
     */
    public function getLikeWidth()
    {
        return Mage::getStoreConfig(self::XML_PATH_FBFREE_LIKE_WIDTH);
    }

    /**
     * The color scheme of the plugin.
     *
     * @return string
     */
    public function getLikeColor()
    {
        return Mage::getStoreConfig(self::XML_PATH_FBFREE_LIKE_COLOR);
    }

    /**
     * Get name of the configured connect button image
     *
     * @return string
     */
    public function getImageLogin()
    {
        return Mage::getStoreConfig(self::XML_PATH_FBFREE_CONNECT_IMAGE);
    }

    /**
     * Get name of the default connect button image
     *
     * @return string
     */
    public function getDefaultImageLogin()
    {
        return Mage::getStoreConfig(self::XML_PATH_FBFREE_CONNECT_DEFIMAGE);
    }

}