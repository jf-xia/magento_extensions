<?php
/*
 * Arcanum Dev PayPal Enabler
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to arcanumdev@wafunotamago.com so we can send you a copy immediately.
 *
 * @category	Magento Checkout/Shopping Cart Extension
 * @package		Paypal Currency Enabler
 * @author		Amon Antiga 2012/02/26
 * @copyright	Copyright (c) 2012 Arcanum Dev. Y.K.
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Arcanumdev_Paypalenabler_Block_Paypalenabler
	extends Mage_Adminhtml_Block_Abstract
	implements Varien_Data_Form_Element_Renderer_Interface
{

	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$html = <<<HTML
<div style="background:url('http://arcanumdev.wafunotamago.com/media/Products/PaypalEnablerLogo.gif') no-repeat scroll 14px 14px #EAF0EE;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 105px;">
<h2 style="text-align: justify; color:#e46b00;">Any PayPal Method with Any Currency to Any Store!</h2>
<p style="text-align: justify;">This extension is specially developed for Magento online stores, which use PayPal as a payment method. This module will make available on your store every Supported currency from PayPal, but not only this; all the currency will be available for every PayPal Payment method! PayPal Standard, PayPal Express, PayPal Pro. You can now use the system more convenient for you and offer to your client all the supported currencies!</p>
<h2 style="text-align: justify; color:#e46b00;">How It Works:</h2>
<p style="text-align: justify;">Differently from other PayPal commercial extension, Arcanum Dev. PayPal Currency Enabler (PCE) do not bypass the Main Core PayPal Module, but rewrite and optimize its functionality for a REAL multi-currency capability.</p>
<h2 style="text-align: justify; color:#e46b00;">Features:</h2>
<ul style="text-align: justify;">
<li><strong>1. All PayPal Method Supported.</strong><br/>
Other commercial extension will exclusively enable the multi-currency only for PayPal Standard; differently with Arcanum Dev. PayPal Currency Enabler you can now choose the PayPal Payment Method which is best for you and your precious clients! PayPal Standard, PayPal Express Checkout and PayPal Pro are now fully available for you!</li><br/>
<li><strong>2. Real Magento Core integration.</strong><br/>
The Magento Core PayPal Module is really well developed and structured. The core module, before sending all the order and client information to PayPal for process the payment, checks every possible error on the order, such as integrity of the prices, compatibility of the currency, integrity of totals, client data, and many other validations. This in order to be certain that all the information are absolutely correct before establish a communication with PayPal. There is only one problem it does it only on the store base currency.<br/>
Others PayPal module Commercial extension, will totally by-pass these controls, completely losing the integrity of the system. All these extensions let the core PayPal module to do all the validations on the base currency, then right before sending the information to PayPal the information are replaced with the vales on the current currency, therefore there is no validation of integrity on any of the new values.<br/>
Arcanum Dev. PayPal Currency Enabler will instead rewrite and optimize the full PayPal Process, All the controls and validations are made directly on the selected currency, then sent to PayPal. This is very important since all prices validation has always to be made on the transaction currency. Moreover, the full potentiality of the core module will keep its integrity granting the accurate stability of the module.<br/>
Now, with Arcanum Dev. PayPal Currency Enabler, you and your clients will always have a reliable and stable module, with all the currencies and methods available!</li><br/>
<li><strong>3. Transfer Cart Line Items fully operative.</strong><br/>
Other commercial extension, by overriding the validation process, will encounter a difficulty based on the rounding of the prices of the current currency use. Some of these commercial extensions overpass this problem by totally disabling the "Transfer Cart Line Items" function, other commercial extension overpass the problem with "workarounds" which will result on having, on the best case scenario, the quantity for the line item set to 1 and the price of the product replaced with the subtotal for the line Item. <br/>
Arcanum Dev. PayPal Currency Enabler keeps the total integrity of the core module allowing a total and reliable stability of the "Transfer Cart Line Items" function.<br/>
Now, with Arcanum Dev. PayPal Currency Enabler, you and your clients will never lose the great commodity of "Transfer Cart Line Items"!</li><br/>
<li><strong>4. No more double shipping charge and random errors.</strong><br/>
Another common problem occurred by overriding the controls and validation of PayPal core module, is the Shipping be wrongly quoted, double charged or missing. This because the shipping price is strictly handled by the validation process, in occurrence of an inaccuracy with the prices the Core may exclude the shipping to be treated as a line item and be integrated as "Shipping", or vice versa. If the validation process is by-passed the shipping may be charge double, or wrongly converted or may be not recognized at all.<br/>
Arcanum Dev. PayPal Currency Enabler keeps always the total integrity of the core module, therefore is not possible to encounter such problem, simply because, if such an error is generated, it will not pass the core validation; therefore the Payment will not be processed.<br/>
Now, with Arcanum Dev. PayPal Currency Enabler, you can finally sit back and relax without the worry of having your client complaining for double or wrong charges, or spending your precious time to check every single order for be sure that the correct shipping charges are paid.</li><br/>
<li><strong>5. Easy to use.</strong><br/>
Yet, another problem generated by by-passing the Core PayPal Module, is that you will need a lot of variable and options to be set on the backend of your store for limit at minimum the generation of errors, which it will not exclude the possible generation of errors.<br/>
Other commercial extension will therefore need a control panel on the backend of your store with a lot of variable, options and worries to be checked and set. If these options and variables are not set correctly may result on the module not working properly, making you losing a lot of time and resources for wait for support or for resolve the problem on your own.<br/>
Arcanum Dev. PayPal Currency Enabler do not modify or corrupt the integrity of PayPal Core Module, therefore there is no need of any setting or control panel, simply because all the control are already made by the PayPal Core Module.<br/>
Now, with Arcanum Dev. PayPal Currency Enabler, you can simply install the module and start producing immediately without any delay or expenses.</li><br/>
<li><strong>6. No tweaks, fine tuning or update required!</strong><br/>
Many commercial extensions require a lot of configuration and setting often with also a strict syntax, remember to use a space between the words, remember to put the ";" etc. This often ends up as an extension difficult to set up and to managed, with a great probability of errors from mistype or inaccuracy.<br/>
Arcanum Dev. PayPal Currency Enabler do not require any setting or any modification, you need only to install it. Once the installation is done Arcanum Dev. PayPal Currency Enabler is ready and enable for you to use it right away!</li><br/>
<li><strong>7. Automatic Currencies Range Update.</strong><br/>
Arcanum Dev. PayPal Currency Enabler makes immediately available for you every currency supported by PayPal. If PayPal updates their currencies range then also your shop will automatically reflect the update without you doing nothing!<br/>
You don't need to inform the module which currency are available from PayPal, or which use decimal rates or not. Everything is fully automated and most importantly <strong>LIGHT & FAST!</strong></li><br/>
<li><strong>8. NO PHP Encoders.</strong><br/>
All our solution are <strong>ABSOLUTLY</strong> PHP encoding <strong>FREE</strong>!
This for granting you a solution which will never require aditional resurce from your server, such as loader or decoder, which may drastically reduce your store performance.
All our extensions will allow you, if necessary, to further improve or modify the code to better suit your store and clients needs.
Naturally if you have the necessity to apply personalization to the code, but you don't posses the necessary knowldege you can freely <a href="mailto:arcanumdev@wafunotamago.com">contact us.</a> directly for an estimate.</li>
</ul><br/>
<p style="text-align: justify;">These are just some of the great advantages offer by this module, so why lose time and resources with other extensions? With Arcanum Dev. PayPal Currency Enabler <strong>you will simply have all the functionality and integrity of the Core module with the integration of every PayPal allowed currencies!</strong></p>
<p><em>Arcanum Dev.</em></p>
</div>
HTML;
		return $html;
	}
}
