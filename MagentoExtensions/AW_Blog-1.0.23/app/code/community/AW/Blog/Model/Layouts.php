<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-L.txt
 *
 * @category   AW
 * @package    AW_Blog
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class AW_Blog_Model_Layouts{
    
	protected $_options;
    
    public function toOptionArray()
    {
        if (!$this->_options) {
            $layouts = array();
			$node = Mage::getConfig()->getNode('global/cms/layouts') ? Mage::getConfig()->getNode('global/cms/layouts') : Mage::getConfig()->getNode('global/page/layouts');
			
			foreach ($node->children() as $layoutConfig) {
				$this->_options[] = array(
				   'value'=>(string)$layoutConfig->template,
				   'label'=>(string)$layoutConfig->label
				);
			}
			
		}
        return $this->_options;
    }
}
