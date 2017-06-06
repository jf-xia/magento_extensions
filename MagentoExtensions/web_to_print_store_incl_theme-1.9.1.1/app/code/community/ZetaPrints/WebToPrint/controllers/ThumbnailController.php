<?php

class ZetaPrints_WebToPrint_ThumbnailController
  extends Mage_Core_Controller_Front_Action
  implements ZetaPrints_Api {

  public function getAction () {
    if (!$this->getRequest()->has('guid'))
        return;

    $guid = $this->getRequest()->get('guid');

    $width = 0;
    if ($this->getRequest()->has('width'))
      $width = (int) $this->getRequest()->get('width');

    $height = 0;
    if ($this->getRequest()->has('height'))
      $height = (int) $this->getRequest()->get('height');

    //Check if width or height is setted
    if (($width + $height) != 0)
      $guid = str_replace('.', "_{$width}x{$height}.", $guid);

    $url = Mage::getStoreConfig('webtoprint/settings/url') . '/thumb/' . $guid;

    $response = zetaprints_get_content_from_url($url);

    if (!zetaprints_has_error($response)) {
      $headers = $response['content']['header'];

      if (is_array($headers))
        $this->getResponse()
          ->setHeader('Last-Modified', $headers['Last-Modified'], true)
          ->setHeader('ETag', $headers['ETag'], true)
          ->setHeader('Pragma', '', true)
          ->setHeader('Cache-Control', 'public', true)
          ->setHeader('Cache-Control', $headers['Cache-Control'])
          ->setHeader('Expires', '', true)
          ->setHeader('Content-Type', $headers['Content-Type'] , true)
          ->setHeader('Content-Length', $headers['Content-Length'], true);
      else {
        $type = explode('.', $guid);

        if (count($type) == 2)
          $type = $type[1];

        if ($type == 'jpg')
          $type = 'jpeg';

        $this->getResponse()
          ->setHeader('Content-Type', 'image/' . $type);
        }

      $this->getResponse()->setBody($response['content']['body']);
    }
  }
}
?>
