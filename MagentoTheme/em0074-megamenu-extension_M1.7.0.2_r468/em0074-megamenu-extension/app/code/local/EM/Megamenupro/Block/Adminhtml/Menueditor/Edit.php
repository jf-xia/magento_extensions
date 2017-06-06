<?php

class EM_Megamenupro_Block_Adminhtml_Menueditor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
		
		$model	=	Mage::registry('megamenupro_data');
		if ($model->getId()) {
            $this->setTemplate('em_megamenupro/edit.phtml');
			
			$this->_removeButton('save');
			
			$this->_addButton('edit', array(
				'label'     => Mage::helper('adminhtml')->__('Save Item'),
				'onclick'   => 'savefrom()',
				'class'     => 'save',
			), 1);
        }
				
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'megamenupro';
        $this->_controller = 'adminhtml_megamenupro';
        
        $this->_updateButton('save', 'label', Mage::helper('megamenupro')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('megamenupro')->__('Delete Item'));
		
		$this->_removeButton('reset');
		$this->_removeButton('delete');
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
		
		$translatedString = array(
			'Insert Image...' => Mage::helper('adminhtml')->__('Insert Image...'),
			'Insert Media...' => Mage::helper('adminhtml')->__('Insert Media...'),
			'Insert File...'  => Mage::helper('adminhtml')->__('Insert File...')
		);
		
		$this->_formScripts[] = '
			if ("undefined" != typeof(Translator)) {
				Translator.add(' . Zend_Json::encode($translatedString) . ');
			}
			
			 openEditorPopup = function(url, name, specs, parent) {
                    if ((typeof popups == "undefined") || popups[name] == undefined || popups[name].closed) {
                        if (typeof popups == "undefined") {
                            popups = new Array();
                        }
                        var opener = (parent != undefined ? parent : window);
                        popups[name] = opener.open(url, name, specs);
                    } else {
                        popups[name].focus();
                    }
                    return popups[name];
                }

                closeEditorPopup = function(name) {
                    if ((typeof popups != "undefined") && popups[name] != undefined && !popups[name].closed) {
                        popups[name].close();
                    }
                }

		';

    }
	
	/**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/megamenupro');
    }
	
	public function getConfig(){
		return Mage::getSingleton('cms/wysiwyg_config')->getConfig();
	}

    public function getHeaderText()
    {
        if( Mage::registry('megamenupro_data') && Mage::registry('megamenupro_data')->getId() ) {
            return Mage::helper('megamenupro')->__("Edit Menu '%s'", $this->htmlEscape(Mage::registry('megamenupro_data')->getName()));
        } else {
            return Mage::helper('megamenupro')->__('Add New Menu');
        }
    }
}