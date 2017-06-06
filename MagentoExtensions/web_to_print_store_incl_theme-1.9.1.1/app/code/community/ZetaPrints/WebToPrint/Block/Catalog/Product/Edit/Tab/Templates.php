<?php

class ZetaPrints_WebToPrint_Block_Catalog_Product_Edit_Tab_Templates extends Mage_Adminhtml_Block_Widget_Grid {
  public function __construct() {
    parent::__construct();
    $this->setId('webtoprint_templates_grid');
    $this->setDefaultSort('guid');
    $this->setUseAjax(true);
  }

  protected function _prepareCollection () {
    $this->setCollection(Mage::getModel('webtoprint/template')->getCollection());
    return parent::_prepareCollection();
  }

  protected function _prepareColumns () {
    $this->addColumn('selected', array(
      'header_css_class' => 'a-center',
      'type'      => 'radio',
      'html_name'      => 'product[webtoprint_template]',
      'values'    => array($this->get_template_guid ()),
      'align'     => 'center',
      'index'     => 'guid',
      'sortable'  => false,
    ));

    $this->addColumn('title', array(
      'header'    => Mage::helper('catalog')->__('Name'),
      'sortable'  => true,
      'index'     => 'title' ));

    $this->addColumn('created', array(
      'type'      => 'datetime',
      'header'    => Mage::helper('catalog')->__('Created'),
      'sortable'  => true,
      'index'     => 'date' ));
  }

   public function getMainButtonsHtml () {
    return parent::getMainButtonsHtml()
            . $this->getChildHtml('web_to_print_source_button');
  }

  private function get_template_guid () {
    return Mage::registry('product')->getWebtoprintTemplate();
  }

  public function getGridUrl() {
    return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/templates', array('_current' => true));
  }

  protected function _prepareLayout () {
    $productType = Mage::registry('product')->getTypeId();
    $name = sprintf('ZetaPrints %s products creation', $productType);

    $profileId = $this->_getProfileId($name);

    if ($profileId)
      $this->_addSourceButton($profileId);

    return parent::_prepareLayout();
  }

  protected function getUpdateProfileAction ($profileId) {
    $url = $this->getUrl('*/*/updateProfile',
                         array('profile-id' => $profileId,
                               'product-id'
                                 => Mage::registry('product')->getId()) );

    return "window.location='{$url}'";
  }

  protected function _getProfileId (
                                $name = 'ZetaPrints simple products creation') {
    $profileModel = Mage::helper('webtoprint')->getProfileByName($name);

    if ($profileModel instanceof Mage_Dataflow_Model_Profile)
      return $profileModel->getId();

    return null;
  }

  protected function _addSourceButton ($profileId) {
    $this->setChild('web_to_print_source_button',
                    $this
                      ->getLayout()
                      ->createBlock('adminhtml/widget_button')
                      ->setData(array(
                        'label' => Mage::helper('webtoprint')
                                     ->__('Use this product as source'),
                        'onclick' => $this->getUpdateProfileAction($profileId),
                        'class' => 'task'
                      )) );

    return $this;
  }

}

?>
