<?php
/**
 * Redirect to AlipayCrossborder
 *
 * @category   NeedTool
 * @package     NeedTool_AlipayCrossborder
 * @name        NeedTool_AlipayCrossborder_Block_Standard_Redirect
 * @author      NeedTool.com <cs@needtool.com>
 */
class NeedTool_AlipayCrossborder_Block_Redirect extends Mage_Core_Block_Abstract
{

	protected function _toHtml()
	{
		
		$quote = Mage::getModel('checkout/session')->getQuote();
		Mage::log($quote);
		$standard = Mage::getModel('alipaycrossborder/payment');
        $form = new Varien_Data_Form();
        $form->setAction($standard->getAlipayCrossborderUrl())
            ->setId('alipaycrossborder_payment_checkout')
            ->setName('alipaycrossborder_payment_checkout')
            ->setMethod('GET')
            ->setUseContainer(true);
        foreach ($standard->setOrder($this->getOrder())->getStandardCheckoutFormFields() as $field => $value) {
            $form->addField($field, 'hidden', array('name' => $field, 'value' => $value));
        }

        $formHTML = $form->toHtml();

        $html = '<html><body>';
        $html.= $this->__('You will be redirected to Alipay in a few seconds.');
        $html.= $formHTML;
        $html.= '<script type="text/javascript">document.getElementById("alipaycrossborder_payment_checkout").submit();</script>';
        $html.= '</body></html>';


        return $html;
    }
}