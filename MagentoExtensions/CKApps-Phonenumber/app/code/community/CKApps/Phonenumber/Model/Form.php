<?php
/**
 * Diglin
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
 * @category    Diglin
 * @package     CKApps_Phonenumber
 * @copyright   Copyright (c) 2011 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CKApps_Phonenumber_Model_Form extends Mage_Customer_Model_Form
{
   public function validateData(array $data){
       
       $errors = parent::validateData($data);
       
       if(isset($data['phonenumber'])){
           $model = Mage::getModel('customer/customer');

           $customerId = Mage::app()->getFrontController()->getRequest()->getParam('customer_id');
           if(!$customerId){
               $customerId = Mage::app()->getFrontController()->getRequest()->getParam('id');
           }
           
           if (isset($data['website_id']) && $data['website_id'] !== false) {
                $websiteId = $data['website_id'];
            } elseif ($customerId) {
                $customer = $model->load($customerId);
                $websiteId = $customer->getWebsiteId();
                if($customer->getPhonenumber() == $data['phonenumber']){ // don't make any test if the user has already the phonenumber
                    return $errors;
                }
            } else {
                $websiteId = Mage::app()->getWebsite()->getId();
            }
           
           
           $result = $model->customerPhonenumberExists($data['phonenumber'], $websiteId);
           if($result && $result->getId() != Mage::app()->getFrontController()->getRequest()->getParam('id')
               && $result->getId() != Mage::getSingleton('customer/session')->getCustomerId('id')){
               $message = Mage::helper('customer')->__("Phonenumber already exists");
               if($errors === true){
                   $errors = array();
               }
               $errors = array_merge($errors, array($message));
           }
       }
       
       if (count($errors) == 0) {
            return true;
       }
       
       return $errors;
   }
}
