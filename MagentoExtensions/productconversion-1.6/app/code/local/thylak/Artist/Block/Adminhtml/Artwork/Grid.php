<?php

class Thylak_Artist_Block_Adminhtml_Artwork_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
   public function __construct()
  {
      parent::__construct();
      $this->setId('artworkGrid');
      $this->setDefaultSort('artwork_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
		$id = $this->getRequest()->getParam('id');
		$artwork = Mage::getModel('artist/artwork')->load($id);
		$custid= $artwork->filename;
		//$id = Mage::getSingleton('customer/session')->getCustomerId();
		$collection = Mage::getModel('artist/artwork')->getCollection();
		$collection->addFieldToFilter('artist_id', $id);
		$this->setCollection($collection);
		return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('artwork_id', array(
          'header'    => Mage::helper('artist')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'artwork_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('artist')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	   

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('artist')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('artist')->__('Edit'),
                        'url'       => array('base'=> 'artist/adminhtml_artist/editartwork'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
      if ( Mage::getSingleton('adminhtml/session')->getArtworkData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getArtworkData());
          Mage::getSingleton('adminhtml/session')->setArtworkData(null);
      } elseif ( Mage::registry('artwork_data') ) {
          $form->setValues(Mage::registry('artwork_data')->getData());
      }

	  
      return parent::_prepareColumns();
  }


  public function getRowUrl($row)
  {
      return $this->getUrl('artist/adminhtml_artist/editartwork', array('id' => $row->getId()));
  }
  
   

}