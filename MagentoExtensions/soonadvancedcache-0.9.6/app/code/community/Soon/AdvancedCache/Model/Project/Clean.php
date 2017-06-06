<?php

/**
 * Agence Soon
 *
 * @category    Soon
 * @package     Soon_AdvancedCache
 * @copyright   Copyright (c) 2011 Agence Soon. (http://www.agence-soon.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Hervé G.
 */

/**
 * This is where one can add tags to be cleaned when the
 * Soon_AdvancedCache_Model_Clean::cleanBlockCache() is called.
 * 
 * Cleaning of tags will also be triggered on events that call 
 * Soon_AdvancedCache_Model_Clean::cleanBlockCacheOnEvent().
 * Please see config.xml
 * 
 */
class Soon_AdvancedCache_Model_Project_Clean extends Soon_AdvancedCache_Model_Clean {

    /**
     * Project specific cache tags to clean
     * 
     * @var array
     */
    protected $_projectTagsToClean;
    
    /**
     * Retrieve project specific tags to clean
     * 
     * @return array
     */
    public function getProjectTagsToClean($object, $observer = null) {
        if(null === $this->_projectTagsToClean) {
            $this->_projectTagsToClean = $this->_populateTagsToClean($object, $observer);
        }
        return $this->_projectTagsToClean;
    }
    
    /**
     * This is the method where tags are generated
     * 
     * @return array
     */
    protected function _populateTagsToClean($object, $observer = null) {
        $projectTagsToClean = array();
        
        // Additional code
        if (($object instanceof Mage_Catalog_Model_Product) === true) {
			$projectTagsToClean[] = 'products_count';
		}
        
        return $projectTagsToClean;
    }
}
