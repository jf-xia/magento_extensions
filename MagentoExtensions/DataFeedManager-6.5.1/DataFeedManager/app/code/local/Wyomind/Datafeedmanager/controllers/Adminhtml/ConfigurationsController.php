<?php

class Wyomind_Datafeedmanager_Adminhtml_ConfigurationsController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {

        $this->loadLayout()
                ->_setActiveMenu('catalog/datafeedmanager')
                ->_addBreadcrumb($this->__('Data feed Manager'), ('Data feed Manager'));

        return $this;
    }

    public function indexAction() {

        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('datafeedmanager/configurations')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('datafeedmanager_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('catalog/datafeedmanager')->_addBreadcrumb(Mage::helper('datafeedmanager')->__('Data Feed Manager'), ('Data Feed Manager'));
            $this->_addBreadcrumb(Mage::helper('datafeedmanager')->__('Data Feed Manager'), ('Data Feed Manager'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()
                            ->createBlock('datafeedmanager/adminhtml_configurations_edit'))
                    ->_addLeft($this->getLayout()
                            ->createBlock('datafeedmanager/adminhtml_configurations_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('datafeedmanager')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {

        $this->_forward('edit');
    }

    public function saveAction() {

        // check if data sent
        if ($data = $this->getRequest()->getPost()) {



            // init model and set data
            $model = Mage::getModel('datafeedmanager/configurations');

            if ($this->getRequest()->getParam('id')) {
                $model->load($this->getRequest()->getParam('id'));
            }


            $model->setData($data);

            // try to save it
            try {
                // save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('datafeedmanager')->__('The data feed configuration has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('continue')) {
                    $this->getRequest()->setParam('id', $model->getId());
                    $this->_forward('edit');
                    return;
                }


                // go to grid or forward to generate action
                if ($this->getRequest()->getParam('generate')) {
                    $this->getRequest()->setParam('feed_id', $model->getId());
                    $this->_forward('generate');
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Delete action
     */
    public function deleteAction() {

        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                // init model and delete
                $model = Mage::getModel('datafeedmanager/configurations');
                $model->setId($id);
                // init and load datafeedmanager model


                $model->load($id);
                // delete file
                if ($model->getFeedName() && file_exists($model->getPreparedFilename())) {
                    unlink($model->getPreparedFilename());
                }
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('datafeedmanager')->__('The data feed configuration has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

                $this->_redirect('*/*/');
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('datafeedmanager')->__('Unable to find the data feed configuration to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }

    public function sampleAction() {

        // init and load datafeedmanager model
        $id = $this->getRequest()->getParam('feed_id');


        $datafeedmanager = Mage::getModel('datafeedmanager/configurations');
        $datafeedmanager->setId($id);
        $datafeedmanager->_limit = Mage::getStoreConfig("datafeedmanager/system/preview");

        $datafeedmanager->_display = true;


        $datafeedmanager->load($id);
        try {
            $content = $datafeedmanager->generateFile();
            if ($datafeedmanager->_demo) {
                $this->_getSession()->addError(Mage::helper('datafeedmanager')->__("Invalid license."));
                Mage::getConfig()->saveConfig('datafeedmanager/license/activation_code', '', 'default', '0');
                Mage::getConfig()->cleanCache();
                $this->_redirect('*/*/');
            }
            else
                print($content);
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/');
        } catch (Exception $e) {

            $this->_getSession()->addError($e->getMessage());
            $this->_getSession()->addException($e, Mage::helper('datafeedmanager')->__('Unable to generate the data feed.'));
            $this->_redirect('*/*/');
        }
    }

    public function generateAction() {

        // init and load datafeedmanager model
        $id = $this->getRequest()->getParam('feed_id');

        $datafeedmanager = Mage::getModel('datafeedmanager/configurations');
        $datafeedmanager->setId($id);
        $limit = $this->getRequest()->getParam('limit');
        $datafeedmanager->_limit = $limit;


        // if datafeedmanager record exists
        if ($datafeedmanager->load($id)) {


            try {
                $datafeedmanager->generateFile();
                $ext = array(1 => 'xml', 2 => 'txt', 3 => 'csv');
                if ($datafeedmanager->_demo) {
                    $this->_getSession()->addError(Mage::helper('datafeedmanager')->__("Invalid license."));
                    Mage::getConfig()->saveConfig('datafeedmanager/license/activation_code', '', 'default', '0');
                    Mage::getConfig()->cleanCache();
                }
                else
                    $this->_getSession()->addSuccess(Mage::helper('datafeedmanager')->__('The data feed "%s" has been generated.', $datafeedmanager->getFeedName() . '.' . $ext[$datafeedmanager->getFeedType()]));
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->addException($e, Mage::helper('datafeedmanager')->__('Unable to generate the data feed.'));
            }
        } else {
            $this->_getSession()->addError(Mage::helper('datafeedmanager')->__('Unable to find a data feed to generate.'));
        }

        // go to grid
        $this->_redirect('*/*/');
    }

    function libraryAction() {

        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $tableEet = $resource->getTableName('eav_entity_type');
        $select = $read->select()->from($tableEet)->where('entity_type_code=\'catalog_product\'');
        $data = $read->fetchAll($select);
        $typeId = $data[0]['entity_type_id'];

        function cmp($a, $b) {

            return ($a['attribute_code'] < $b['attribute_code']) ? -1 : 1;
        }

        /*  Liste des  attributs disponible dans la bdd */

        $attributesList = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($typeId)
                ->addSetInfo()
                ->getData();
        $selectOutput = null;
        $attributesList[] = array("attribute_code" => "qty", "frontend_label" => "Quantity");
        $attributesList[] = array("attribute_code" => "is_in_stock", "frontend_label" => "Is in stock");
        usort($attributesList, "cmp");

        $tabOutput = '<div id="dfm-library"><ul><h3>Attribute groups</h3> ';
        $contentOutput = '<table >';





        $tabOutput .=" <li><a href='#attributes'>Base Attributes</a></li>";


        $contentOutput .="<tr><td><a name='attributes'></a><b>Base Attributes</b></td></tr>";
        foreach ($attributesList as $attribute) {


            if (!empty($attribute['attribute_code']))
                $contentOutput.= "<tr><td><span class='pink'>{" . $attribute['attribute_code'] . "}</span></td></tr>";
        }

       
        
        $class = new Wyomind_Datafeedmanager_Model_Configurations;
        $myCustomAttributes = new Wyomind_Datafeedmanager_Model_MyCustomAttributes;
        foreach ($myCustomAttributes->_getAll() as $group => $attributes) {
            $tabOutput .=" <li><a href='#" . $group . "'> " . $group . "</a></li>";
            $contentOutput .="<tr><td><a name='" . $group . "'></a><b>" . $group . "</b></td></tr>";
            foreach ($attributes as $attr) {
                $contentOutput.= "<tr><td><span class='pink'>{" . $attr . "}</span></td></tr>";
            }
        }


 $tabOutput .=" <li><a target='_blank' href='http://wyomind.com/data-feed-manager-magento.html?src=dfm-library&directlink=documentation#Special_attributes'>Special Attributes</a></li>";
       

      /*

        $myCustomOptions = new MyCustomoptions;
        foreach ($myCustomOptions->_getAll() as $group => $Options) {
            $tabOutput .=" <li><a href='#" . $group . "'> " . $group . "</a></li>";
            $contentOutput .="<tr><td><a name='" . $group . "'></a><b>" . $group . "</b></td></tr>";
            foreach ($Options as $opt) {
                $contentOutput.= "<tr><td><span class='pink'>{attribute_code,<span class='green'>[" . $opt . "]</span>}</span></td></tr>";
            }
        }
*/
        $contentOutput .="</table></div>";
        $tabOutput .= '</ul>';
        die($tabOutput . $contentOutput);
    }

}
