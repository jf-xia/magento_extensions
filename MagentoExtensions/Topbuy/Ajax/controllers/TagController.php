<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Tag
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Tagged products controller
 *
 * @category   Mage
 * @package    Mage_Tag
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Topbuy_Ajax_TagController extends Mage_Core_Controller_Front_Action
{
    //http://www.topbuy.com.au/ajax/tag/list/name/Exclusive%20New%20TopBuy%20Deals
    public function listAction()
    {
        $tagName = $this->getRequest()->getParam('name');
        $tag = Mage::getModel('tag/tag')->loadByName($tagName);
//        $tagId = $this->getRequest()->getParam('tagId');
//        $tag = Mage::getModel('tag/tag')->load($tagId);
        $searchUrl=Mage::getBaseUrl().'catalogsearch/result/?q='.$tagName;
        if(!$tag->getName() || !$tag->getStatus()) {
            $this->_redirect('catalogsearch/result/index',array('q'=>$tagName));	
            return;
        }
        Mage::register('current_tag', $tag);

        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        $this->_initLayoutMessages('tag/session');
        $this->renderLayout();
    }
    /**
     * Saving tag and relation between tag, customer, product and store
     * http://www.topbuy.com.au/ajax/tag/save/?productTagName=Freebie&isActive=1&product=TBLC-XX1025735,TBLC-XX1025465,TBLC-XX1021519
     */
    public function saveAction()
    {
        $tagName    = (string) $this->getRequest()->getQuery('productTagName');
        $skus  = (string) $this->getRequest()->getParam('product');
        $isActive = (int) $this->getRequest()->getParam('isActive');
//        $key = (int) $this->getRequest()->getParam('key');
        $skuArray = explode(",",$skus);
//        print_r($skuArray);
//        if ($key==t2o0p1b1uy){
        foreach ($skuArray as $sku) {
            if(strlen($tagName) && $sku) {
                $session = Mage::getSingleton('catalog/session');
                $product = Mage::getModel('catalog/product')
                        ->loadByAttribute('sku',$sku);
                if(!$product){
                    echo $msg = 'Unable to save tag(s). getId null for sku:'.$sku;
                    return;
//                        $session->addError($this->__($msg));
                } else {
                    try {
                        $customerId = 2;
                        $storeId = 1;//Mage::app()->getStore()->getId();

                        $tagModel = Mage::getModel('tag/tag');

                        // added tag relation statuses
                        $counter = array(
                            Mage_Tag_Model_Tag::ADD_STATUS_NEW => array(),
                            Mage_Tag_Model_Tag::ADD_STATUS_EXIST => array(),
                            Mage_Tag_Model_Tag::ADD_STATUS_SUCCESS => array(),
                            Mage_Tag_Model_Tag::ADD_STATUS_REJECTED => array()
                        );

//                        $tagNamesArr = $this->_cleanTags($this->_extractTags($tagName));
//                        foreach ($tagNamesArr as $tagName) {
                            // unset previously added tag data
                            $tagModel->unsetData()
                                ->loadByName($tagName);

                            $tagModel->setName($tagName)
                                ->setFirstCustomerId($customerId)
                                ->setFirstStoreId($storeId)
                                ->setStatus($isActive)
                                ->save();
                            $relationStatus = $tagModel->saveRelation($product->getId(), $customerId, $storeId);
                            $counter[$relationStatus][] = $tagName;
//                        }
                        $this->_fillMessageBox($counter);
                    } catch (Exception $e) {
                        Mage::logException($e);
                        echo $msg = 'Unable to save tag(s).';
                        $session->addError($this->__($msg));
                    }
                }
            }
        }
        echo 'success!';
	die();
//        }else {
//            echo 'The key is wrong, please ask admin for the keys';
//        }
//        $this->_redirectReferer();
    }

    /**
     * Checks inputed tags on the correctness of symbols and split string to array of tags
     *
     * @param string $tagNamesInString
     * @return array
     */
    protected function _extractTags($tagNamesInString)
    {
        return explode("\n", preg_replace("/(\'(.*?)\')|(\s+)/i", "$1\n", $tagNamesInString));
    }

    /**
     * Clears the tag from the separating characters.
     *
     * @param array $tagNamesArr
     * @return array
     */
    protected function _cleanTags(array $tagNamesArr)
    {
        $helper = Mage::helper('core');
        foreach( $tagNamesArr as $key => $tagName ) {
            $tagNamesArr[$key] = trim($tagNamesArr[$key], '\'');
            $tagNamesArr[$key] = trim($tagNamesArr[$key]);
            $tagNamesArr[$key] = $helper->escapeHtml($tagNamesArr[$key]);
            if( $tagNamesArr[$key] == '' ) {
                unset($tagNamesArr[$key]);
            }
        }
        return $tagNamesArr;
    }

    /**
     * Fill Message Box by success and notice messages about results of user actions.
     *
     * @param array $counter
     * @return void
     */
    protected function _fillMessageBox($counter)
    {
        $session = Mage::getSingleton('catalog/session');

        if (count($counter[Mage_Tag_Model_Tag::ADD_STATUS_NEW])) {
            $session->addSuccess($this->__('%s tag(s) have been accepted for moderation.',
                count($counter[Mage_Tag_Model_Tag::ADD_STATUS_NEW]))
            );
        }

        if (count($counter[Mage_Tag_Model_Tag::ADD_STATUS_EXIST])) {
            foreach ($counter[Mage_Tag_Model_Tag::ADD_STATUS_EXIST] as $tagName) {
                $session->addNotice($this->__('Tag "%s" has already been added to the product.' ,$tagName));
            }
        }

        if (count($counter[Mage_Tag_Model_Tag::ADD_STATUS_SUCCESS])) {
            foreach ($counter[Mage_Tag_Model_Tag::ADD_STATUS_SUCCESS] as $tagName) {
                $session->addSuccess($this->__('Tag "%s" has been added to the product.' ,$tagName));
            }
        }

        if (count($counter[Mage_Tag_Model_Tag::ADD_STATUS_REJECTED])) {
            foreach ($counter[Mage_Tag_Model_Tag::ADD_STATUS_REJECTED] as $tagName) {
                $session->addNotice($this->__('Tag "%s" has been rejected by administrator.' ,$tagName));
            }
        }
    }
}
