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
 * @package     Diglin_Username
 * @copyright   Copyright (c) 2011 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Diglin_Username_Model_Entity_Setup extends Mage_Customer_Model_Entity_Setup{

    public function getDefaultEntities(){
        $entities = parent::getDefaultEntities();
        
        $entities['customer']['attributes'] = array(
					'created_at' => array(
                        'type'          => 'static',
                        'label'         => 'Created At',
                        'visible'       => false,
                        'required'      => false,
                        'input'         => 'date',
                    ),
                    'mobile' => array(
                        'type'          => 'static',
                        'label'         => 'Mobile',
                        'visible'       => true,
                        'required'      => false,
                        'sort_order'    => 80,
                    ),
	        	    'customer_address' => array(
						'entity_model'  =>'customer/customer_address',
						'table' => 'customer/address_entity',
						'additional_attribute_table' => 'customer/eav_attribute',
						'entity_attribute_collection' => 'customer/address_attribute_collection',
                    ),
					'username' => array(
						'type'	=> 'varchar',
						'input'	=> 'text',
						'label'         => 'Username',
						'sort_order'    => 44,  
					)
        );
        
        return $entities;
    }
}