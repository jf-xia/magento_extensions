<?php

class ZetaPrints_WebToPrint_Model_Quote_Item extends Mage_Sales_Model_Quote_Item {
  public function representProduct ($product) {
    if ($product->getWebtoprintTemplate())
      return false;

    return parent::representProduct($product);
  }

  public function compare ($item) {
    //Get model for info_buyRequest option
    $option_model = $item->getOptionByCode('info_buyRequest');

    //Unserialize its value
    $options = unserialize($option_model->getValue());

    //Check if quote item represents web-to-print product.
    //If it does then do not merge these items.
    if (isset($options['zetaprints-TemplateID']))
      return false;

    return parent::compare($item);
  }
}

?>
