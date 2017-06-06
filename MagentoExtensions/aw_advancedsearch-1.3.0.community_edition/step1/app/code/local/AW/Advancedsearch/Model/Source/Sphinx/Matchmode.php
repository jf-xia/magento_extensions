<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedsearch
 * @version    1.3.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Advancedsearch_Model_Source_Sphinx_Matchmode extends AW_Advancedsearch_Model_Source_Abstract
{
    protected function _toOptionArray()
    {
        $_helper = $this->_getHelper();
        try {
            include_once BP.DS.'lib'.DS.'Sphinx'.DS.'sphinxapi.php';
            $options = array();
            if(defined('SPH_MATCH_ALL')) {
                $options[] = array('value' => SPH_MATCH_ALL, 'label' => $_helper->__('Match all query words'));
            }
            if(defined('SPH_MATCH_ANY')) {
                $options[] = array('value' => SPH_MATCH_ANY, 'label' => $_helper->__('Match any of the query words'));
            }
            if(defined('SPH_MATCH_PHRASE')) {
                $options[] = array('value' => SPH_MATCH_PHRASE, 'label' => $_helper->__('Match query as a phrase'));
            }
            if(defined('SPH_MATCH_BOOLEAN')) {
                $options[] = array('value' => SPH_MATCH_BOOLEAN, 'label' => $_helper->__('Match query as a boolean expression'));
            }
            if(defined('SPH_MATCH_EXTENDED')) {
                $options[] = array('value' => SPH_MATCH_EXTENDED, 'label' => $_helper->__('SPH_MATCH_EXTENDED'));
            }
            if(defined('SPH_MATCH_FULLSCAN')) {
                $options[] = array('value' => SPH_MATCH_FULLSCAN, 'label' => $_helper->__('SPH_MATCH_FULLSCAN'));
            }
            if(defined('SPH_MATCH_EXTENDED2')) {
                $options[] = array('value' => SPH_MATCH_EXTENDED2, 'label' => $_helper->__('SPH_MATCH_EXTENDED2'));
            }
            return $options;
        } catch(Exception $ex) {
            return array(array('value' => 0, 'label' => $_helper->__('SPH_MATCH_ALL')));
        }
    }
}
