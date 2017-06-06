<?php
/** 
 * @category    Mana
 * @package     ManaPro_FilterSeoLinks
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/**
 * This class observes certain (defined in etc/config.xml) events in the whole system and provides public methods - 
 * handlers for these events.
 * @author Mana Team
 *
 */
class ManaPro_FilterSeoLinks_Model_Observer extends Mage_Core_Helper_Abstract {
    protected function _findLayeredNavigationBlock($candidates) {
        foreach ($candidates as $candidate) {
            if ($layer = Mage::getSingleton('core/layout')->getBlock($candidate)) {
                return $layer;
            }
        }
        return null;
    }
	/**
	 * Adds filters to category page title (handles event "controller_action_layout_render_before_catalog_category_view")
	 * @param Varien_Event_Observer $observer
	 */
	public function addCategoryTitle($observer) {
	    if (/* @var $layer Mana_Filters_Block_View */ ($layer = $this->_findLayeredNavigationBlock(array(
	        'mana.catalog.leftnav',
	        'mana.catalog.contentnav',
	        'mana.catalog.rightnav',
        )))
        && ($head = Mage::getSingleton('core/layout')->getBlock('head')) && $layer->getChild('layer_state'))
	    {
            /** @var $head Mage_Page_Block_Html_Head */
            $head->getTitle();
            $globalVars = array(
	            'title' => $head->getData('title'),
	            'site' => (object)array(
	                'title' => $head->getDefaultTitle(),
	            ),
	            '_values' => array(),
	            '_break' => false,
	            '_layer' => $layer,
	            '_filterPattern' => null,
	            '_valuePattern' => null,
	            '_valuePatterns' => array(),
	        );

	        foreach ($layer->getChild('layer_state')->getActiveFilters() as /* @var $item Mana_Filters_Model_Item */ $item) {
	            $globalVars['_values'][] = (object)array(
	                '_obj' => $item,
	                'title' => $layer->stripTags($item->getLabel()),
	            );
	        }

            if (file_exists(BP.'/app/etc/m_page_titles.xml')) {
                $xml = simplexml_load_string(file_get_contents(BP.'/app/etc/m_page_titles.xml'));
            }
            else {
                $xml = simplexml_load_string(file_get_contents(BP.'/app/code/local/ManaPro/FilterSeoLinks/etc/m_page_titles.xml'));
            }

	        foreach ($xml->children() as $ruleName => $rule) {
	            switch ($ruleName) {
	                case 'case': $this->_processCase($rule, $globalVars); break;
	                case 'values': $this->_processValues($rule, $globalVars); break;
	                case 'filters': $this->_processFilters($rule, $globalVars); break;
	                case 'finally': $this->_processFinally($rule, $globalVars); break;
                    default: throw new Exception('Not implemented');
	            }
	            if ($globalVars['_break']) {
	                break;
	            }
	        }

	        $head->setTitle($globalVars['title']);


	    }
	}
	
	/**
     * @param SimpleXMLElement $rule
     * @param array $variables
     */
	protected function _processCase($rule, &$globalVars) {
        $locals = array(
            '_matches' => true,
            '_found' => array(),
        );
        if (!empty($rule['category_id'])) {
            $locals['_matches'] = $globalVars['_layer']->getLayer()->getCurrentCategory()->getId() == ((string)$rule['category_id']);
        }
        if ($locals['_matches']) {
            foreach ($rule->children() as $instructionName => $instruction) { /* @var $instruction SimpleXMLElement */
                switch ($instructionName) {
                    case 'if': $this->_processCaseIf($instruction, $globalVars, $locals); break;
                    case 'set': if ($locals['_matches']) $this->_processSet($instruction, $globalVars, $locals); break;
                    default: throw new Exception('Not implemented');
                }
            }
        }
        if ($locals['_matches']) {
            foreach ($locals['_found'] as $index) {
                unset($globalVars['_values'][$index]);
            }
            if (!empty($rule['break'])) {
                $globalVars['_break'] = true;
            }
        }
	}
	/**
     * @param SimpleXMLElement $instruction
     * @param array $globals
     * @param array $locals
     */
	protected function _processCaseIf($instruction, &$globalVars, &$locals) {
	    foreach ($globalVars['_values'] as $index => $value) {
	        if ((string)$instruction['filter_code'] == $value->_obj->getFilter()->getFilterOptions()->getCode() &&
	            (string)$instruction['value_label'] == $globalVars['_layer']->stripTags($value->_obj->getLabel()))
	        {
	            if (isset($instruction['as'])) {
	                $locals[(string)$instruction['as']] = $value;
	            }
                $locals['_found'][] = $index;
	            return;
	        }
	    }
	    $locals['_matches'] = false;
	}
	protected function _processSet($instruction, &$globalVars, $locals = null) {
	    foreach ($instruction->attributes() as $key => $value) {
	        $globalVars[$key] = $this->_processValue((string)$value, $globalVars, $locals);
	    }
	}
	protected function _processValue($__template, $__globalVars, $__locals = null) {
	    extract($__globalVars);
	    if ($__locals) {
	        extract($__locals);
	    }
	    return eval(' return "'.$__template.'";');
	}
	protected function _processValues($rule, &$globalVars) {
	    $locals = array();
        foreach ($rule->children() as $instructionName => $instruction) { /* @var $instruction SimpleXMLElement */
            switch ($instructionName) {
                case 'if': $this->_processApplyIf($instruction, $globalVars, $locals); break;
                case 'apply':
                    if (isset($locals['code'])) {
                        $this->_processApply($instruction, $globalVars, '_valuePatterns', $locals['code']);
                    }
                    else {
                        $this->_processApply($instruction, $globalVars, '_valuePattern');
                    }
                    break;
                default: throw new Exception('Not implemented');
            }
        }
    }
    protected function _processApplyIf($instruction, &$globalVars, &$locals) {
        $locals['code'] = (string)$instruction['filter_code'];
    }
    protected function _processFilters($rule, &$globalVars) {
        foreach ($rule->children() as $instructionName => $instruction) { /* @var $instruction SimpleXMLElement */
            switch ($instructionName) {
                case 'apply': $this->_processApply($instruction, $globalVars, '_filterPattern'); break;
                default: throw new Exception('Not implemented');
            }
        }
    }
    protected function _processApply($instruction, &$globalVars, $var, $key = null) {
        $pattern = array(
            'pattern' => (string)$instruction['pattern'],
            'glue' => (string)$instruction['glued_by'],
            'lastGlue' => isset($instruction['last_glued_by'])
                ? (string)$instruction['last_glued_by']
                : (string)$instruction['glued_by']
        );
        if ($key === null) {
            $globalVars[$var] = $pattern;
        }
        else {
            $globalVars[$var][$key] = $pattern;
        }
    }
    protected function _processFinally($rule, &$globalVars) {
        $valuePattern = array('pattern' => '{$value->title}', 'glue' => ', ', 'lastGlue' => ', ');
        $valuePattern = $globalVars['_valuePattern'] ? $globalVars['_valuePattern'] : $valuePattern;
        if ($globalVars['_filterPattern']) {
            $filters = array();
            $filterValues = array();
            foreach ($globalVars['_values'] as $value) {
                $code = $value->_obj->getFilter()->getFilterOptions()->getCode();
                if (!isset($filters[$code])) {
                    $filters[$code] = array(
                        'pattern' => isset($globalVars['_valuePatterns'][$code])
                            ? $globalVars['_valuePatterns'][$code]
                            : $valuePattern,
                        'options' => $value->_obj->getFilter()->getFilterOptions(),
                        'values' => array(),
                    );
                }
                $filters[$code]['values'][] = $this->_processValue($filters[$code]['pattern']['pattern'], $globalVars, compact('value'));
            }
            foreach ($filters as $filter) {
                $values = $this->_implode($filter['values'], $filter['pattern']);
                $filter = (object)array('title' => $filter['options']->getName());
                $filterValues[] = $this->_processValue($globalVars['_filterPattern']['pattern'], $globalVars, compact('values', 'filter'));
            }
            $globalVars['filters'] = $this->_implode($filterValues, $globalVars['_filterPattern']);
            if (!$globalVars['filters']) {
                return;
            }
        }
        else {
            $values = array();
            foreach ($globalVars['_values'] as $value) {
                $values[] = $this->_processValue($valuePattern['pattern'], $globalVars, compact('value'));
            }
            $globalVars['values'] = $this->_implode($values, $valuePattern);
            if (!$globalVars['values']) {
                return;
            }
        }

        foreach ($rule->children() as $instructionName => $instruction) { /* @var $instruction SimpleXMLElement */
            switch ($instructionName) {
                case 'set': $this->_processSet($instruction, $globalVars); break;
                default: throw new Exception('Not implemented');
            }
        }
    }
    protected function _implode($values, $pattern) {
        $count = count($values);
        if ($count == 0) {
            return '';
        }
        elseif ($count == 1) {
            return $values[0];
        }
        elseif ($count == 2) {
            return implode($pattern['lastGlue'], $values);
        }
        else {
            return implode($pattern['glue'], array_slice($values, 0, $count - 1)).$pattern['lastGlue'].$values[$count - 1];
        }
    }
    /**
     * Adds filters to category page title (handles event "controller_action_layout_render_before_catalogsearch_result_index")
     * @param Varien_Event_Observer $observer
     */
    public function addSearchTitle($observer) {
        if (/* @var $layer Mana_Filters_Block_View */ ($layer = $this->_findLayeredNavigationBlock(array(
            'mana.catalogsearch.leftnav',
            'mana.catalog.contentnav',
            'mana.catalog.rightnav',
        ))) && $layer->getChild('layer_state'))
        {
            $title = array();
            foreach ($layer->getChild('layer_state')->getActiveFilters() as $filter) {
                $title[] = $layer->stripTags($filter->getLabel());
            }
            if ($title = implode(', ', $title)) {
                $head = Mage::getSingleton('core/layout')->getBlock('head');
                $head->setTitle($head->getTitle().': '. $title);
            }
        }
    }


    protected function _noindex($layerModel) {
        if (($head = Mage::getSingleton('core/layout')->getBlock('head'))) {
            /* @var $head Mage_Page_Block_Html_Head */
            $robots = $head->getRobots();
            /* @var $layer Mage_Catalog_Model_Layer */ $layer = Mage::getSingleton($layerModel);
            foreach (explode(',', Mage::getStoreConfig('mana_filters/seo/no_index')) as $noindexProcessorName) {
                if (!$noindexProcessorName) {
                    continue;
                }

                $noindexProcessor = Mage::getModel((string)Mage::getConfig()->getNode('manapro_filterseolinks/noindex')->$noindexProcessorName->model);
                $noindexProcessor->process($robots, $layerModel);
            }
            $head->setRobots($robots);
        }
    }
    /**
     * Adds NOINDEX if configured so (handles event "controller_action_layout_render_before_catalog_category_view")
     * @param Varien_Event_Observer $observer
     */
    public function noindexCategoryView($observer) {
        $this->_noindex('catalog/layer');
    }
    /**
     * Adds NOINDEX if configured so (handles event "controller_action_layout_render_before_catalogsearch_result_index  ")
     * @param Varien_Event_Observer $observer
     */
    public function noindexSearchResult($observer) {
        $this->_noindex('catalogsearch/layer');
    }
    /**
     * Adds NOINDEX if configured so (handles event "controller_action_layout_render_before_cms_page_view")
     * @param Varien_Event_Observer $observer
     */
    public function noindexCmsPage($observer) {
        $this->_noindex('catalog/layer');
    }
    /**
     * Adds NOINDEX if configured so (handles event "controller_action_layout_render_before_cms_index_index")
     * @param Varien_Event_Observer $observer
     */
    public function noindexCmsIndex($observer) {
        $this->_noindex('catalog/layer');
    }
    /**
     * REPLACE THIS WITH DESCRIPTION (handles event "m_before_load_filter_collection")
     * @param Varien_Event_Observer $observer
     */
    public function addLowerCaseNameColumnToFilterCollection($observer) {
        /* @var $collection Mana_Filters_Resource_Filter2_Store_Collection */ $collection = $observer->getEvent()->getCollection();
        $collection->getSelect()->columns('LOWER(main_table.name) AS lower_case_name');
    }
}