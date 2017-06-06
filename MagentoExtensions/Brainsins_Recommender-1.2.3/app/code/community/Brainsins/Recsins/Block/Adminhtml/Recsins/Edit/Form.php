<?php

/*
 * BrainSINS' Magento Extension allows to integrate the BrainSINS
 * personalized product recommendations into a Magento Store.
 * Copyright (c) 2011 Social Gaming Platform S.R.L.
 *
 * This file is part of BrainSINS' Magento Extension.
 *
 *  BrainSINS' Magento Extension is free software: you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  Foobar is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Please do not hesitate to contact us at info@brainsins.com
 *
 */

final class BS_Radios extends Varien_Data_Form_Element_Radios {

    public function __construct($attributes=array()) {
        parent::__construct($attributes);
    }

    protected function _optionToHtml($option, $selected) {
        $html = '<input type="radio"' . $this->serialize(array('name', 'class', 'style'));
        if (is_array($option)) {
            $html.= 'value="' . $this->_escape($option['value']) . '"  id="' . $this->getHtmlId() . $option['value'] . '"';
            if ($option['value'] == $selected) {
                $html.= ' checked="checked"';
            }
            $html.= ' />';
            $html.= '<label class="inline" for="' . $this->getHtmlId() . $option['value'] . '">' . $option['label'] . '</label>';
        } elseif ($option instanceof Varien_Object && $selected) {
            $html.= 'id="' . $this->getHtmlId() . $option->getValue() . '"' . $option->serialize(array('label', 'title', 'value', 'class', 'style'));
            if (in_array($option->getValue(), $selected)) {
                $html.= ' checked="checked"';
            }
            $html.= ' />';
            $html.= '<label style = "margin-left:10px" class="inline" for="' . $this->getHtmlId() . $option->getValue() . '">' . $option->getLabel() . '</label>';
        } else {
            $html.= 'id="' . $this->getHtmlId() . $option->getValue() . '"' . $option->serialize(array('label', 'title', 'value', 'class', 'style'));
            $html.= ' />';
            $html.= '<label style = "margin-left:10px" class="inline" for="' . $this->getHtmlId() . $option->getValue() . '">' . $option->getLabel() . '</label>';
        }
        if ($option['additional_text']) {
            $html .= $option['additional_text'];
        }
        $html.= $this->getSeparator() . "\n";
        return $html;
    }

}

final class BS_Form extends Varien_Data_Form {

    private $_extraHtml = "";

    public function __construct($attributes=array()) {
        parent::__construct($attributes);
    }

    public function toHtml() {

        return parent::toHtml() . $this->_extraHtml;
    }

    public function setExtraHtml($html) {
        $this->_extraHtml = $html;
    }

}

class Brainsins_Recsins_Block_Adminhtml_Recsins_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    //private $dev = true;
    private $dev = false;

    protected function _prepareForm() {

        Mage::getConfig()->cleanCache();

        $linkBaseUrl;

        if ($this->dev) {
            $linkBaseUrl = "http://dev-analytics.brainsins.com";
        } else {
            $linkBaseUrl = "http://analytics.brainsins.com";
        }

        $form = new BS_Form(array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                        )
        );

        $uploading = Mage::getStoreConfig('brainsins/UPLOADING_STATUS');

        if (isset($uploading) && $uploading == '1') {

            $continueUploadScript = "
			<script type='text/javascript'>
			try{
				var upload_catalog = document.getElementById('upload_catalog');
				var edit_form = document.getElementById('edit_form');
				if (upload_catalog.value != 'abort') {
					upload_catalog.value = 'uploading';
				}
				edit_form.submit();
			} catch (e){};
			</script>
			";

            $form->setExtraHtml($continueUploadScript);
        }

        $data = $this->getRequest()->getPost();

        $key = Mage::getStoreConfig('brainsins/BSKEY');
        $key = $key ? $key : '';


        // print some non-visible html code to the page


        $js = "<script type='text/javascript'>" . PHP_EOL;
        $js .= "function bsImportConfig(){" . PHP_EOL;
        $js .= "var import_config = document.getElementById('import_config');" . PHP_EOL;
        $js .= "var edit_form = document.getElementById('edit_form');" . PHP_EOL;
        $js .= "import_config.value = '1';" . PHP_EOL;
        $js .= "edit_form.submit();" . PHP_EOL;
        $js .= "}" . PHP_EOL;
        $js .= "function bsUploadCatalog(){" . PHP_EOL;
        $js .= "var upload_catalog = document.getElementById('upload_catalog');" . PHP_EOL;
        $js .= "var edit_form = document.getElementById('edit_form');" . PHP_EOL;
        $js .= "upload_catalog.value = 'upload';" . PHP_EOL;
        $js .= "edit_form.submit();" . PHP_EOL;
        $js .= "}" . PHP_EOL;
        $js .= "function bsUploadingCatalog(){" . PHP_EOL;
        $js .= "var upload_catalog = document.getElementById('upload_catalog');" . PHP_EOL;
        $js .= "var edit_form = document.getElementById('edit_form');" . PHP_EOL;
        $js .= "upload_catalog.value = 'uploading';" . PHP_EOL;
        $js .= "edit_form.submit();" . PHP_EOL;
        $js .= "}" . PHP_EOL;
        $js .= "function bsAbortCatalogUpload(){" . PHP_EOL;
        $js .= "var upload_catalog = document.getElementById('upload_catalog');" . PHP_EOL;
        $js .= "var edit_form = document.getElementById('edit_form');" . PHP_EOL;
        $js .= "upload_catalog.value = 'abort';" . PHP_EOL;
        $js .= "edit_form.submit();" . PHP_EOL;
        $js .= "}" . PHP_EOL;
        $js.= "</script>" . PHP_EOL;
        echo($js);

        $style = "<style>";
        $style .= ".entry-edit .field-row label {width: 250px;}";

        $style .= "</style>";
        echo($style);

        // create form elements

        $clientsLinkUrl = "javascript:window.open('" . $linkBaseUrl . "/settings/accountinfo')";
        $nonClientsLinkUrl = "javascript:window.open('http://www.brainsins.es/tarifas')";


        $form->setUseContainer(true);
        $helper = $this->helper("recsins");

        $text = new Varien_Data_Form_Element_Text(array('name' => 'bskey_text'));
        $text->setId('bskey_text');
        $text->setValue($key);

        $keyQuestion = new Varien_Data_Form_Element_Label(array('value' => $helper->__('Do not have a BrainSINS KEY?')));
        $clientsLink = new Varien_Data_Form_Element_Link(array('value' => $helper->__('I am a BrainSINS client'), 'href' => $clientsLinkUrl, 'style' => 'padding-left:20px;'));
        $nonClientsLink = new Varien_Data_Form_Element_Link(array('value' => $helper->__('I am not a BrainSINS client yet'), 'href' => $nonClientsLinkUrl, 'style' => 'padding-left:20px;'));


        $enabledValue = Mage::getStoreConfig('brainsins/BS_ENABLED');

        if (!$enabledValue && $enabledValue !== '0') {
            Mage::getModel('core/config')->saveConfig('brainsins/BS_ENABLED', '1');
            $enabledValue = '1';
        }

        $enabledOptions = new BS_Radios(array('id' => 'bsenableoptions', 'name' => 'bsenableoptions', 'separator' => '<br><br>'));
        $enabledOption = new Varien_Data_Form_Element_Radio(array('id' => 'bsenabledoption', 'name' => 'bsenabledoption', 'label' => $helper->__("Enabled"), 'value' => '1'));
        $disabledOption = new Varien_Data_Form_Element_Radio(array('id' => 'bsdisabledoption', 'name' => 'bsdisabledoption', 'label' => $helper->__("Disabled"), 'value' => '0'));

        if ($enabledValue === '1') {
            $enabledOptions->setValue(array('1'));
        } else {
            $enabledOptions->setValue(array('0'));
        }

        $enabledOptions->setValues(array($enabledOption, $disabledOption));



        $keyFieldSet = new Varien_Data_Form_Element_Fieldset(array('name' => 'bskeyfs'));
        $keyFieldSet->setId('bskeyfs');
        $keyFieldSet->addElement(new Varien_Data_Form_Element_Label(array('value' => $helper->__('BrainSINS KEY'), 'bold' => true)));
        $keyFieldSet->addElement($text);
        $keyFieldSet->addElement($keyQuestion);
        $keyFieldSet->addElement($clientsLink);
        $keyFieldSet->addElement($nonClientsLink);

        $enableFieldSet = new Varien_Data_Form_Element_Fieldset(array('name' => 'bsenablefs'));
        $enableFieldSet->setId('bsenablefs');
        $enableFieldSet->addElement(new Varien_Data_Form_Element_Label(array('value' => $helper->__('Enable / Disable Extension'), 'bold' => true)));
        $enableFieldSet->addElement($enabledOptions);


        $model = Mage::getModel('recsins/recommender');
        $entries = $model->getCollection()->addAttributeToSelect('bskey');
        $defaultText = $helper->__("Do not show");

        $defaultHomeOption = new Varien_Data_Form_Element_Radio();
        $defaultHomeOption->setLabel($defaultText);
        $defaultHomeOption->setValue(0);

        $defaultProductOption = new Varien_Data_Form_Element_Radio();
        $defaultProductOption->setLabel($defaultText);
        $defaultProductOption->setValue(0);

        $defaultCartOption = new Varien_Data_Form_Element_Radio();
        $defaultCartOption->setLabel($defaultText);
        $defaultCartOption->setValue(0);

        $defaultCheckoutOption = new Varien_Data_Form_Element_Radio();
        $defaultCheckoutOption->setLabel($defaultText);
        $defaultCheckoutOption->setValue(0);


        $homeRadioOptions = array();
        $productRadioOptions = array();
        $cartRadioOptions = array();
        $checkoutRadioOptions = array();

        $homeRadioOptions[] = $defaultHomeOption;
        $productRadioOptions[] = $defaultProductOption;
        $cartRadioOptions[] = $defaultCartOption;
        $checkoutRadioOptions[] = $defaultCheckoutOption;

        $homeSelected = Mage::getStoreConfig('brainsins/BS_HOME_RECOMMENDER');
        $productSelected = Mage::getStoreConfig('brainsins/BS_PRODUCT_RECOMMENDER');
        $cartSelected = Mage::getStoreConfig('brainsins/BS_CART_RECOMMENDER');
        $checkoutSelected = Mage::getStoreConfig('brainsins/BS_CHECKOUT_RECOMMENDER');



        foreach ($entries as $entry) {
            $entry->load($entry->getId());
            $page = $entry->getRecommender_page();

            $radio = new Varien_Data_Form_Element_Radio();
            $radioLink = "javascript:window.open(\"" . $linkBaseUrl . "/settings/editrecommenderstyle?idRecommender=" . $entry->getRecommender_id() . "\")";
            $radio->setLabel($entry->getRecommender_name());
            $radio->setValue($entry->getRecommender_id());
            $radio->setAdditional_text("<input type='button' onclick='" . $radioLink . "' value='" . $helper->__("Edit Style") . "' style='margin-left:20px'>");

            switch ($page) {
                case 1:
                    $optionsHome[$entry->getRecommender_id()] = $entry->getRecommender_name();
                    $homeRadioOptions[] = $radio;
                    break;
                case 2:
                    $optionsProduct[$entry->getRecommender_id()] = $entry->getRecommender_name();
                    $productRadioOptions[] = $radio;
                    break;
                case 3:
                    $optionsCheckout[$entry->getRecommender_id()] = $entry->getRecommender_name();
                    $checkoutRadioOptions[] = $radio;
                    break;
                case 4:
                    $optionsCart[$entry->getRecommender_id()] = $entry->getRecommender_name();
                    $cartRadioOptions[] = $radio;
                    break;
            }
        }

        // home

        $homeRecommenders = new BS_Radios(array('name' => 'bshome_recommenders', 'separator' => '<br><br>'));
        $homeRecommenders->setId('bshome_recommenders');
        $homeRecommenders->setValues($homeRadioOptions);
        if ($homeSelected !== null) {
            $homeRecommenders->setValue(array($homeSelected));
        } else {
            $homeRecommenders->setValue(array(0));
        }

        $homeFieldSet = new Varien_Data_Form_Element_Fieldset(array('name' => 'bshomerecommendersfs'));
        $homeFieldSet->setId('bshomerecommendersfs');
        $homeFieldSet->addElement(new Varien_Data_Form_Element_Label(array('value' => $helper->__('Home Page Recommender'), 'bold' => true)));
        $homeFieldSet->addElement($homeRecommenders);

        // product

        $productRecommenders = new BS_Radios(array('name' => 'bsproduct_recommenders', 'separator' => '<br><br>'));
        $productRecommenders->setId('bsproduct_recommenders');
        $productRecommenders->setValues($productRadioOptions);
        if ($productSelected !== null) {
            $productRecommenders->setValue(array($productSelected));
        } else {
            $productRecommenders->setValue(array(0));
        }

        $productFieldSet = new Varien_Data_Form_Element_Fieldset(array('name' => 'bsproductrecommendersfs'));
        $productFieldSet->setId('bsproductrecommendersfs');
        $productFieldSet->addElement(new Varien_Data_Form_Element_Label(array('value' => $helper->__('Product Page Recommender'), 'bold' => true)));
        $productFieldSet->addElement($productRecommenders);

        // cart

        $cartRecommenders = new BS_Radios(array('name' => 'bscart_recommenders', 'separator' => '<br><br>'));
        $cartRecommenders->setId('bscart_recommenders');
        $cartRecommenders->setValues($cartRadioOptions);
        if ($cartSelected !== null) {
            $cartRecommenders->setValue(array($cartSelected));
        } else {
            $cartRecommenders->setValue(array(0));
        }

        $cartFieldSet = new Varien_Data_Form_Element_Fieldset(array('name' => 'bscartrecommendersfs'));
        $cartFieldSet->setId('bscartrecommendersfs');
        $cartFieldSet->addElement(new Varien_Data_Form_Element_Label(array('value' => $helper->__('Cart Page Recommender'), 'bold' => true)));
        $cartFieldSet->addElement($cartRecommenders);

        // checkout

        $checkoutRecommenders = new BS_Radios(array('name' => 'bscheckout_recommenders', 'separator' => '<br><br>'));
        $checkoutRecommenders->setId('bscheckout_recommenders');
        $checkoutRecommenders->setValues($checkoutRadioOptions);
        if ($checkoutSelected !== null) {
            $checkoutRecommenders->setValue(array($checkoutSelected));
        } else {
            $checkoutRecommenders->setValue(array(0));
        }

        $checkoutFieldSet = new Varien_Data_Form_Element_Fieldset(array('name' => 'bscheckoutrecommendersfs'));
        $checkoutFieldSet->setId('bscheckoutrecommendersfs');
        $checkoutFieldSet->addElement(new Varien_Data_Form_Element_Label(array('value' => $helper->__('Checkout Page Recommender'), 'bold' => true)));
        $checkoutFieldSet->addElement($checkoutRecommenders);


        $advancedFieldSet = new Varien_Data_Form_Element_Fieldset(array('name' => 'bsadvancedoptionsfs'));
        $advancedFieldSet->setId('bsadvancedoptionsfs');
        $advancedFieldSet->addElement(new Varien_Data_Form_Element_Label(array('value' => $helper->__('Advanced Options'), 'bold' => true)));

        $cautionText = "(" . $helper->__ ("please do not modify these values unless you are told so by BrainSINS' support team") . ")";

        $advancedFieldSet->addElement(new Varien_Data_Form_Element_Label(array('value' => $cautionText)));
        $advancedFieldSet->addElement(new Varien_Data_Form_Element_Label(array('value' => $helper->__("Catalog upload page size"), 'bold' => 'true')));

        $radio1 = new Varien_Data_Form_Element_Radio();
        $radio1->setLabel("1");
        $radio1->setValue("page1");
        $radio1->setAdditional_text("");

        $radio10 = new Varien_Data_Form_Element_Radio();
        $radio10->setLabel("10");
        $radio10->setValue("page10");
        $radio10->setAdditional_text("");

        $radio20 = new Varien_Data_Form_Element_Radio();
        $radio20->setLabel("20");
        $radio20->setValue("page20");
        $radio20->setAdditional_text("");

        $radio50 = new Varien_Data_Form_Element_Radio();
        $radio50->setLabel("50");
        $radio50->setValue("page50");
        $radio50->setAdditional_text("");

        $radio100 = new Varien_Data_Form_Element_Radio();
        $radio100->setLabel("100");
        $radio100->setValue("page100");
        $radio100->setAdditional_text("");

        $radio200 = new Varien_Data_Form_Element_Radio();
        $radio200->setLabel("200");
        $radio200->setValue("page200");
        $radio200->setAdditional_text("");

        $advancedOptions = array();
        $advancedOptions[] = $radio1;
        $advancedOptions[] = $radio10;
        $advancedOptions[] = $radio20;
        $advancedOptions[] = $radio50;
        $advancedOptions[] = $radio100;
        $advancedOptions[] = $radio200;

        $advancedRadios = new BS_Radios(array('name' => 'bsadvanced', 'separator' => '<br>'));
        $advancedRadios->setId("bsadvanced_options");
        $advancedRadios->setValues($advancedOptions);

        $pageSize = Mage::getStoreConfig('brainsins/BS_PAGE_SIZE');

        if (!isset($pageSize) || !($pageSize != 'page1' || $pageSize != 'page10' || $pageSize != 'page20' || $pageSize != 'page50' || $pageSize != 'page100' || $pageSize != 'page200')) {
            $pageSize = 'page50';
        }
        
        $advancedRadios->setValue(array($pageSize));

        $advancedFieldSet->addElement($advancedRadios);


        // buttons

        $hiddenImport = new Varien_Data_Form_Element_Hidden(array('name' => 'import_config'));
        $hiddenImport->setId("import_config");
        $hiddenImport->setValue("0");

        $hiddenUpload = new Varien_Data_Form_Element_Hidden(array('name' => 'upload_catalog'));
        $hiddenUpload->setId("upload_catalog");
        $hiddenUpload->setValue("0");

        $importButton = new Varien_Data_Form_Element_Button(array('name' => 'import_config_button'));
        $importButton->setId('import_config_button');
        $importButton->setValue($helper->__('Import Recommenders'));
        $importButton->setOnclick("bsImportConfig()");

        $uploadButton = new Varien_Data_Form_Element_Button(array('name' => 'upload_catalog_button'));
        $uploadButton->setId('upload_catalog_button');
        $uploadButton->setValue($helper->__('Send Catalog'));
        $uploadButton->setOnclick("bsUploadCatalog()");

        $abortButton = new Varien_Data_Form_Element_Button(array('name' => 'abort_upload_button'));
        $abortButton->setId('upload_catalog_button');
        $abortButton->setValue($helper->__('Abort Catalog Upload'));
        $abortButton->setOnclick("bsAbortCatalogUpload()");

        $abortButton2 = new Varien_Data_Form_Element_Button(array('name' => 'abort_upload_button2'));
        $abortButton2->setId('upload_catalog_button2');
        $abortButton2->setValue($helper->__('Abort Catalog Upload'));
        $abortButton2->setOnclick("bsAbortCatalogUpload()");


        // build the final form

        if (isset($uploading) && $uploading == '1') {
            $form->addElement($abortButton2);
        }

        $form->addElement($keyFieldSet);
        $form->addElement($enableFieldSet);
        $form->addElement($homeFieldSet);
        $form->addElement($productFieldSet);
        $form->addElement($cartFieldSet);
        $form->addElement($checkoutFieldSet);
        $form->addElement($advancedFieldSet);
        $form->addElement($hiddenImport);
        $form->addElement($hiddenUpload);
        $form->addElement($importButton);

        if (isset($uploading) && $uploading == '1') {
            $form->addElement($abortButton);
        } else {
            $form->addElement($uploadButton);
        }
        $this->setForm($form);       
        
        return $form;
    }

}