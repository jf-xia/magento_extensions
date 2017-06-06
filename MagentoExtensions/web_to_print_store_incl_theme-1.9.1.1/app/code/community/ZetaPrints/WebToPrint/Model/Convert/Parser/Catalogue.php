<?php

class ZetaPrints_WebToPrint_Model_Convert_Parser_Catalogue
  extends  Mage_Dataflow_Model_Convert_Parser_Abstract
  implements ZetaPrints_Api {

  public function parse() {
    $url = Mage::getStoreConfig('webtoprint/settings/url');
    $key = Mage::getStoreConfig('webtoprint/settings/key');

    //Always print debug information. Issue #80
    $this->debug = true;

    if ($url)
      $this->notice("ZetaPrints URL: {$url}");
    else
      $this->error("ZetaPrints URL is empty");

    if ($key)
      $this->notice('ZetaPrints API Key: ' . substr($key, 0, 6). '&hellip;');
    else
      $this->error("ZetaPrints API Key is empty");

    $catalogs = zetaprints_get_list_of_catalogs($url, $key);

    if ($catalogs === null) {
      $this->error('Error in parsing catalogs detailes xml');
      return;
    } else if (is_string($catalogs)) {
      $this->error("Error in receiving catalogs: {$catalogs}");
      return;
    }

    if (!count($catalogs)) {
      $this->warning('No catalogs');
      return;
    }

    $isCreateSubCategories = $this->getAction()
                                  ->getParam('create-subcategories', 'false')
                               == 'true';

    $total_number_of_catalogs = 0;
    $number_of_ignored_catalogs = 0;
    $number_of_created_catalogues = 0;
    $number_of_not_created_catalogues = 0;

    $line = 0;

    $helper = Mage::helper('webtoprint');

    foreach ($catalogs as $catalog) {
      $total_number_of_catalogs++;

      if ($catalog['templates'] == 0) {
        $this->warning("No templates in catalog {$catalog['title']}");

        $number_of_ignored_catalogs++;

        continue;
      }

      $category = $helper->getCategory($catalog['title'], true);

      if (!$category && !$category->getId()) {
        $this->notice("Can't create catalogue '{$catalog['title']}'");

        $number_of_not_created_catalogues++;

        continue;
      }

      if ($isCreateSubCategories)
        foreach (explode(',', $catalog['keywords']) as $name) {
          if (!$name)
            continue;

          $total_number_of_catalogs++;

          $subCategory = $helper->getCategory($name, true, $category);

          if (!$subCategory && !$subCategory->getId()) {
            $this->notice("Can't create catalogue '{$catalog['title']}/{$name}'");

            $number_of_not_created_catalogues++;

            continue;
          }

          $this->notice("Catalogue '{$catalog['title']}/{$name}' was created sucessfully");

          $number_of_created_catalogues++;
        }

      $this->notice("Catalogue '{$catalog['title']}' was created sucessfully");

      $number_of_created_catalogues++;
    }

    $this->notice("Total number of catalogs: {$total_number_of_catalogs}");
    $this->notice("Number of ignored catalogs: {$number_of_ignored_catalogs}");
    $this->notice("Number of created catalogues: {$number_of_created_catalogues}");
    $this->notice("Number of not created catalogues: {$number_of_not_created_catalogues}");
  }

  public function unparse() {}

  private function error ($message) {
    $this->addException($message, Mage_Dataflow_Model_Convert_Exception::ERROR);
  }

  private function warning ($message) {
    $this->addException($message, Mage_Dataflow_Model_Convert_Exception::WARNING);
  }

  private function notice ($message) {
    $this->addException($message, Mage_Dataflow_Model_Convert_Exception::NOTICE);
  }

  private function debug ($message) {
    if ($this->debug)
      $this->notice($message);
  }
}
