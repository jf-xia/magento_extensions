<?php

class Flagbit_EpoqInterface_Model_Container_TrackProduct extends Enterprise_PageCache_Model_Container_Abstract
{
	 /**
     * Redirect to content processing on new message
     *
     * @param string $content
     * @return bool
     */
    public function applyWithoutApp(&$content)
    {
    	return false;
    }
	
    /**
     * Render block content
     *
     * @return string
     */
    protected function _renderBlock()
    {
        $block = $this->_placeholder->getAttribute('block');
        $template = $this->_placeholder->getAttribute('template');

        $block = new $block;
        $block->setLayout(Mage::app()->getLayout());

        return $block->toHtml();
    }
    
    /**
     * Generate block content
     * @param $content
     */
    public function applyInApp(&$content)
    {
        $this->_applyToContent($content, $this->_renderBlock());
        return true;
    }       
}
