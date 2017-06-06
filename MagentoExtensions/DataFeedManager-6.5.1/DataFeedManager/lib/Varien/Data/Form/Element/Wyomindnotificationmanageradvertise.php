<?php

class Varien_Data_Form_Element_Wyomindnotificationmanageradvertise extends Varien_Data_Form_Element_Abstract
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('link');
    }

    /**
     * Generates element html
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = $this->getBeforeElementHtml();
        
        
        $html .= '<div id="'.$this->getHtmlId().'" '.$this->serialize($this->getHtmlAttributes()).'>'. $this->getEscapedValue() . "</div>\n";
        
        if (ini_get('allow_url_fopen') == 1)
            $html .= file_get_contents('http://www.wyomind.com/pubs/current.php');
        else
            $html .= "";
        
        $html .= "
            <script>
            document.observe('dom:loaded',function() {
                $('row_notificationmanager_notificationmanager_advertise').select('td[class=scope-label]')[0].style.display = 'none';
            });
            </script>
        ";
        
        $html .= $this->getAfterElementHtml();
        return $html;
    }

    /**
     * Prepare array of anchor attributes
     *
     * @return array
     */
    public function getHtmlAttributes()
    {
        return array('charset', 'coords', 'href', 'hreflang', 'rel', 'rev', 'name',
            'shape', 'target', 'accesskey', 'class', 'dir', 'lang', 'style',
            'tabindex', 'title', 'xml:lang', 'onblur', 'onclick', 'ondblclick',
            'onfocus', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover',
            'onmouseup', 'onkeydown', 'onkeypress', 'onkeyup');
    }
}
