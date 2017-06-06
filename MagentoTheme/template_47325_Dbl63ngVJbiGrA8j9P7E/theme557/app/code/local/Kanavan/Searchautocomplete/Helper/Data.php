<?php

class Kanavan_Searchautocomplete_Helper_Data extends Mage_Core_Helper_Abstract
{

  public function getSuggestUrl()
  {
        return $this->_getUrl('searchautocomplete/suggest/result', array(
            '_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
        ));
  }

  public function getStyle()
  {
        //$style='';
        $style='
        <style>
.ajaxsearch{border:solid '.Mage::getStoreConfig('searchautocomplete/settings/border_color').' ' .Mage::getStoreConfig('searchautocomplete/settings/border_width').'px}
.ajaxsearch .suggest{background:'.Mage::getStoreConfig('searchautocomplete/suggest/background').'; color:'.Mage::getStoreConfig('searchautocomplete/suggest/suggest_color').'}
.ajaxsearch .suggest .amount{color:'.Mage::getStoreConfig('searchautocomplete/suggest/count_color').'}
.ajaxsearch .preview {background:'.Mage::getStoreConfig('searchautocomplete/preview/background').'}
.ajaxsearch .preview a {color:'.Mage::getStoreConfig('searchautocomplete/preview/pro_title_color').'}
.ajaxsearch .preview .description {color:'.Mage::getStoreConfig('searchautocomplete/preview/pro_description_color').'}
.ajaxsearch .preview img {float:left; border:solid '.Mage::getStoreConfig('searchautocomplete/preview/image_border_width').'px '.Mage::getStoreConfig('searchautocomplete/preview/image_border_color').' }
.header .form-search .ajaxsearch li.selected {background-color:'.Mage::getStoreConfig('searchautocomplete/settings/hover_background').'}
</style>';
return $style;
 }
}
