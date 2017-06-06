<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterSuperSlider
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/**
 * @author Mana Team
 *
 */
class ManaPro_FilterSuperSlider_Model_Attribute extends Mana_Filters_Model_Filter_Attribute {
    public function getLowestPossibleLabel() {
        $items = $this->getItems();
        return $items[0]['label'];
    }
    public function getHighestPossibleLabel() {
        $items = $this->getItems();
        return $items[count($items) - 1]['label'];
    }
    protected function _getLabelByValue($value) {
        foreach ($this->getItems() as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }
    public function getCurrentRangeLowerLabel() {
        $value = $this->getCurrentRangeLowerBound();
        if ($label = $this->_getLabelByValue($value)) {
            return $label;
        }
        else {
            return $this->getLowestPossibleLabel();
        }
    }
    public function getCurrentRangeHigherLabel() {
        $value = $this->getCurrentRangeHigherBound();
        if ($label = $this->_getLabelByValue($value)) {
            return $label;
        }
        else {
            return $this->getHighestPossibleLabel();
        }
    }
    public function getLowestPossibleValue() {
        $items = $this->getItems();
        return $items[0]['value'];
    }
    public function getHighestPossibleValue() {
        $items = $this->getItems();
        return $items[count($items) - 1]['value'];
    }
    public function getCurrentRangeLowerBound() {
        foreach($this->getItems() as $item) {
            if ($item->getMSelected()) {
                return $item->getValue();
            }
        }

        return $this->getLowestPossibleValue();
    }
    public function getCurrentRangeHigherBound() {
        foreach (array_reverse($this->getItems()) as $item) {
            if ($item->getMSelected()) {
                return $item->getValue();
            }
        }

        return $this->getHighestPossibleValue();
    }
    public function getExistingValues() {
        $result = array();
        foreach ($this->getItems() as $item) {
            $urlValue = $item['value'];
            if (((string)Mage::getConfig()->getNode('modules/ManaPro_FilterSeoLinks/active')) == 'true' &&
                Mage::helper('mana_core')->getRoutePath() != 'catalogsearch/result/index')
            {
                $url = Mage::getModel('manapro_filterseolinks/url');
                $urlValue = $url->encodeValue($this->getAttributeModel()->getAttributeCode(), $urlValue);
            }
            $result[] = array('value' => $item['value'], 'label' => $item['label'], 'urlValue' => $urlValue);
        }
        return $result;
    }
    protected function _getItemsData() {
        $selectedOptionIds = $this->getMSelectedValues();

        $attribute = $this->getAttributeModel();
        $this->_requestVar = $attribute->getAttributeCode();

        $key = $this->getLayer()->getStateKey() . '_' . $this->_requestVar;
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $options = $attribute->getFrontend()->getSelectOptions();
            $optionsCount = $this->_getResource()->getCount($this);
            $data = array();

            foreach ($options as $option) {
                if (is_array($option['value'])) {
                    continue;
                }
                if (Mage::helper('core/string')->strlen($option['value'])) {
                    $data[] = $current = array(
                        'label' => $option['label'],
                        'value' => $option['value'],
                        'count' => isset($optionsCount[$option['value']]) ? $optionsCount[$option['value']] : 0,
                        'm_selected' => in_array($option['value'], $selectedOptionIds),
                    );
                }
            }

            $tags = array(
                Mage_Eav_Model_Entity_Attribute::CACHE_TAG . ':' . $attribute->getId()
            );

            $tags = $this->getLayer()->getStateTags($tags);
            if ($sortMethod = $this->getFilterOptions()->getSortMethod()) {
                usort($data, array(Mage::getSingleton('mana_filters/sort'), $sortMethod));
            }
            $first = $last = -1;
            foreach ($data as $index => $current) {
                if ($first == -1) {
                    if ($current['count']) {
                        $first = $index;
                    }
                }
                if ($current['count']) {
                    $last = $index;
                }
            }
            if ($first != -1) {
                $data = array_slice($data, $first, $last - $first + 1);
            }
            else {
                $data = array();
            }

            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock) {
        $filter = $request->getParam($this->_requestVar);
        if (is_array($filter)) {
            return $this;
        }

        // MANA BEGIN: when several filter options can be applied, several labels should be added to layer
        // state, on label for each selected option. Here we assume all option ids to be in URL as one string value
        // separated by '_'
        //        $text = $this->_getOptionText($filter);
        $text = array();
        foreach ($this->getMSelectedValues() as $optionId) {
            $text[] = $this->getAttributeModel()->getFrontend()->getOption($optionId);
        }
        // MANA END

        if ($filter && $text && strpos($filter, '_') !== false) {
            $items = $this->_getItemsData();
            $values = array();
            list($from, $to) = explode('_', $filter);
            $isInside = false;
            foreach ($items as $item) {
                if ($item['value'] == $from) {
                    if ($item['value'] != $to) {
                        $isInside = true;
                    }
                    $values[] = $item['value'];
                }
                elseif ($item['value'] == $to) {
                    $isInside = false;
                    $values[] = $item['value'];
                }
                elseif ($isInside) {
                    $values[] = $item['value'];
                }
            }
            $this->_getResource()->applyFilterToCollection($this, implode('_', $values));
            $this->getLayer()->getState()->addFilter($this->_createItemEx(array(
                'label' => $text[0] .' - ' .$text[count($text) - 1],
                'value' => $filter,
                'm_selected' => true,
                'remove_url' => $this->getRemoveUrl(),
            )));
        }
        return $this;
    }
    public function getItemsCount() {
        $count = count($this->getItems());
        return $count > 1 ? $count : 0;
    }
}