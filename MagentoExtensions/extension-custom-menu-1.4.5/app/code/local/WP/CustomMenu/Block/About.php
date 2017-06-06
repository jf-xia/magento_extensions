<?php

class WP_CustomMenu_Block_About
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = <<<HTML
<div style="background:url('http://www.webandpeople.com/template/public/images/logo.gif') no-repeat scroll 14px 14px #EAF0EE;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 164px;">
    <p>
        <b style="font-size:12px;">WebAndPeople</b>, a family of niche sites, provides small businesses with everything they need to start selling online.
    </p>
    <p>
        <strong>PREMIUM and FREE MAGENTO TEMPALTES and EXTENSIONS</strong><br />
        <a href="http://web-experiment.info" target="_blank">Web-Experiment.info</a> offers a wide choice of nice-looking and easily editable free and premium Magento Themes. At Web-Experiment, you can find free downloads or buy premium tempaltes for the extremely popular Magento eCommerce platform.<br />
        <strong>MAGENTO HOSTING</strong></strong><br />
        <a href="http://magenting.com" target="_blank">Magenting.com</a>, a new and improved hosting solution, is allowing you to easily create, promote, and manage your online store with Magento. Magenting users will receive a valuable set of tools and features, including automatic Magento eCommerce installation, automatic Magento template installation and a free or paid professional Magento hosting account.<br />
        <strong>WEB DEVELOPMENT</strong><br />
        <a href="http://webandpeople.com" target="_blank">WebAndPeople.com</a> is a team of professional Web developers and designers who are some of the best in the industry. WebAndPeople provides Web application development, custom Magento theme designs, and Website design services.<br />
        <br />
    </p>
    <p>
        Our themes and extensions on <a href="http://www.magentocommerce.com/magento-connect/developer/WebAndPeople" target="_blank">MagentoConnect</a><br />
        Should you have any questions <a href="http://webandpeople.com/contact.html" target="_blank">Contact Us</a> or email at <a href="mailto:info@webandpeople.com">info@webandpeople.com</a>
        <br />
    </p>
</div>
HTML;
        return $html;
    }
}
