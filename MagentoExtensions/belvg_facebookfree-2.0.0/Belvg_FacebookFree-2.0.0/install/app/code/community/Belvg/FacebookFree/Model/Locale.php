<?php

class Belvg_FacebookFree_Model_Locale extends Mage_Core_Model_Abstract
{

    /**
     *
     * @var SimpleXMLElement|NULL
     */
    protected $_locales = NULL;

    const FBFREE_DEFAULT_LOCALE = 'en_US';

    /**
     * Read locales
     */
    public function __construct()
    {
        $classArr = explode('_', get_class($this));
        $moduleName = $classArr[0] . '_' . $classArr[1];
        $etcDir = Mage::getConfig()->getModuleDir('etc', $moduleName);

        $fileName = $etcDir . DS . 'locale.xml';
        if (is_readable($fileName)) {
            $localesXml = file_get_contents($fileName);
            $this->_locales = new Varien_Simplexml_Element($localesXml);
        }
    }

    /**
     * Get current FB locale according to the selected store locale
     *
     * @return string
     */
    public function getLocale()
    {
        $store_locale = Mage::app()->getLocale()->getLocaleCode();
        $localeParams = array();

        if (isset($this->_locales->$store_locale)) {
            $localeParams = new Varien_Object($this->_locales->$store_locale->asArray());
        }

        $locale = isset($localeParams['code']) ? $localeParams['code'] : self::FBFREE_DEFAULT_LOCALE;

        return $locale;
    }

}