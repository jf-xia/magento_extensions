<?php
/**
 * Luxe 
 * Bestsellers module
 *
 * @category   Luxe
 * @package    Luxe_Bestsellers
 */

/**
 * Product list toolbar
 *
 * @category    Luxe 
 * @package     Luxe_Bestsellers
 * @author      Yuriy V. Vasiyarov
 */
class Luxe_Bestsellers_Block_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar 
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('luxe/bestsellers/list/toolbar.phtml');
    }

    public function getCurrentMode()
    {
        $mode = $this->getParentBlock()->getDisplayMode();
        if ($mode) {
            return $mode;
        } else {
            return Mage::getStoreConfig('bestsellers/bestsellers/list_mode');
        }
    }

    public function getLimit()
    {
        return intval(Mage::getStoreConfig('bestsellers/bestsellers/num_displayed_products'));
    }
}
