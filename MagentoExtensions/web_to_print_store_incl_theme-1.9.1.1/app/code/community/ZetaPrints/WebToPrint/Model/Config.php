<?php

class ZetaPrints_WebToPrint_Model_Config extends Mage_Core_Model_Config_Base {

  /**
   * Key name for storage of cache data
   *
   * @var string
   */
  const CACHE_KEY = 'WEBTOPRINT';

  /**
   * Tag name for cache type, used in mass cache cleaning
   *
   * @var string
   */
  const CACHE_TAG = 'WEBTOPRINT_CUSTOM_OPTIONS';

  /**
   * Filename that will be collected from different modules
   *
   * @var string
   */
  const CONFIG_FILENAME = 'custom-options.xml';

  /**
   * Initial configuration file template, then merged in one file
   *
   * @var string
   */
  const CONFIG_TEMPLATE = '<?xml version="1.0"?><config><webtoprint /></config>';

  /**
   * Web-to-print options node name
   *
   * @var string
   */
  const WEBTOPRINT_NODE_NAME = 'webtoprint';

  /**
   * Constructor
   *
   * Loading of custom configuration files
   * from different modules
   *
   * @param string $sourceData
   */
  function __construct ($sourceData = null) {
    $tags = array (self::CACHE_TAG);
    $useCache = Mage::app()->useCache(self::CACHE_TAG);

    $this->setCacheId(self::CACHE_KEY);
    $this->setCacheTags($tags);

    if ($useCache && ($cache = Mage::app()->loadCache(self::CACHE_KEY)))
      parent::__construct($cache);
    else {
      parent::__construct(self::CONFIG_TEMPLATE);

      Mage::getConfig()
        ->loadModulesConfiguration(self::CONFIG_FILENAME, $this);

      if ($useCache) {
        $xmlString = $this->getXmlString();
        Mage::app()->saveCache($xmlString, self::CACHE_KEY, $tags);
      }
    }
  }

  public function getOptions ($path = null) {
    if ($path === null)
      $options = $this->getNode(self::WEBTOPRINT_NODE_NAME);
    else
      $options = $this->getNode(self::WEBTOPRINT_NODE_NAME)->descend($path);

    if (!$options)
      return $options;

    $options = $options->asArray();

    if (!is_array($options))
      return array();

    return $this->_prepareOptions($options);
  }

  private function _prepareOptions ($options) {
    //Remove 0 => "" array element for empty tags
    unset($options[0]);

    //Walk throw sub-options
    foreach ($options as $name => $suboptions)
      //If sub option is array and is not array of tag attributes...
      if ($name !== '@' && is_array($suboptions))
        //... the run the function for the sub-option
        $options[$name] = $this->_prepareOptions($suboptions);

    //If the option has tag attributes...
    if (isset($options['@'])) {
      //Move all attributes to the options
      //and prepend their names with @ symbol
      foreach($options['@'] as $name => $value)
        $options["@{$name}"] = $value;

      //Remove array of tag attributes from the option
      unset($options['@']);
    }

    //Return processed options
    return $options;
  }
}
