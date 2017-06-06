<?php

class Wyomind_Datafeedmanager_DatafeedmanagerController extends Mage_Core_Controller_Front_Action {

    public function generateAction() {
        // http://www.example.com/index.php/datafeedmanager/datafeedmanager/generate/id/{data_feed_id}/ak/{YOUR_ACTIVATION_KEY}
        
        $id = $this->getRequest()->getParam('id');
        $ak=$this->getRequest()->getParam('ak');
        
        $activation_key=Mage::getStoreConfig("datafeedmanager/license/activation_key");
        
        if($activation_key==$ak) {


            $datafeedmanager = Mage::getModel('datafeedmanager/configurations');
            $datafeedmanager->setId($id);
            if ($datafeedmanager->load($id)) {
                try {
                    $datafeedmanager->generateFile();
                    die(Mage::helper('datafeedmanager')->__('The data feed "%s" has been generated.', $datafeedmanager->getFeedName()));
                } catch (Mage_Core_Exception $e) {
                    die($e->getMessage());
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                die(Mage::helper('datafeedmanager')->__('Unable to find a data feed to generate.'));
            }
        } else die('Invalid activation key');
    }

}

