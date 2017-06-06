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

class AW_Advancedsearch_ResultController extends Mage_Core_Controller_Front_Action
{
    protected function _getSession()
    {
        return Mage::getSingleton('core/session');
    }

    public function indexAction()
    {
        $resultsHelper = Mage::helper('awadvancedsearch/results');
        $catalogSearchHelper = Mage::helper('catalogsearch');
        $queryText = $catalogSearchHelper->getQueryText();
        $results = $resultsHelper->query($queryText);
        if ($results) {
            $helper = Mage::helper('awadvancedsearch/catalogsearch');
            $helper->addCatalogsearchQueryResults($queryText, $results);
            $helper->setResults($results);
        } else {
            if (method_exists($catalogSearchHelper, 'getOriginalResultUrl')) {
                return $this->_redirectUrl($catalogSearchHelper->getOriginalResultUrl($catalogSearchHelper->getQueryText()));
            } else {
                return $this->_redirectUrl($catalogSearchHelper->getResultUrl($catalogSearchHelper->getQueryText()));
            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }
}
