<?php
class ZetaPrints_WebToPrint_TransController extends Mage_Core_Controller_Front_Action {

  public function indexAction() {
    $out = '';
    $locale_file = Mage::getBaseDir('locale').DS.Mage::app()->getLocale()->getLocaleCode().DS.'zetaprints_w2p.csv';
    if (file_exists($locale_file)) {
      $locale = @file_get_contents($locale_file);
      preg_match_all('/"(.*?)","(.*?)"(:?\r|\n)/', $locale, $array, PREG_PATTERN_ORDER);
      if (is_array($array) && count($array[1]) > 0) {
        $out = 'var trans = {';
        foreach ($array[1] as $key => $value) {
          if (strlen($value) > 0 && strlen($array[2][$key]) > 0) {
            $out .= "'".$value."':'".$array[2][$key]."',";
          }
        }
        $out = substr($out, 0, - 1)."};";
      }
    }
    echo "function zetaprints_trans(msg){".$out."
if(typeof(trans)=='undefined'||typeof(trans[msg])=='undefined')return msg;
else return trans[msg];
}";
  }
}
?>