<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2011 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_CustomerCredit_Model_System_Config_Source_Product extends Mage_Core_Model_Config_Data
{    
    
    public function toOptionArray() {
       
        $options = array(
            array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('None'))
        );
                
        $collection = Mage::getResourceModel('catalog/product_collection')            
            ->setStoreId(Mage::app()->getStore()->getId())
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addAttributeToFilter('type_id', 'virtual')
            ->addAttributeToSelect('name')
            ->setOrder('name', $dir='asc');
                
        if (count($collection)>0) {
            foreach ($collection as $product) {
                if ($product->getSku() && $product->getName()) {
                    $options[] = array('value'=>$product->getSku(), 'label'=>$product->getName());
                }
            }
        }     
        return $options;
    }    
    
}