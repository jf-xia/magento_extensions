<?php
 /*
 * Arcanum Dev AwardPoints
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
 * @category   Magento Sale Extension
 * @package    AwardPoints
 * @copyright  Copyright (c) 2012 Arcanum Dev. Y.K. (http://arcanumdev.wafunotamago.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Arcanumdev_Awardpoints_Block_Awardpoints
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$html = <<<HTML
<div style="background:url('http://arcanumdev.wafunotamago.com/media/Products/AwardPointsLogo.gif') no-repeat scroll 14px 14px #EAF0EE;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 105px;">

<h2 style="text-align: justify; color:#e46b00;">Reward your Customer and improve your sales with many sweets Points!</h2>
<p style="text-align: justify;">You can now practically <strong>improve your sales</strong> and the motivation of your customers with this great extension!<br/>
This module will allow your customers to gather points according to your directives; your customer will have access to points according to the products, the shopping carts, your personal rules for campaign, for website registration, for friends' referral, for their review and much, much more!<br/>
All the point gathered by the customer can be used as discount and you can freely and fully decide all the rules on how these points can be spent!<br/>
Many other commercial extensions for points are already available on Magento, and all these extension, including ours, does pretty much the same with very minimal differences. However an outstanding difference can be found on the price!<br/>
Finally you are now able to use all the <strong>sales potentiality given by sales points with a reasonable price!</strong></p>
<h2 style="text-align: justify; color:#e46b00;">Custom Design Compatible!*</h2>
<p style="text-align: justify;">All our Magento Extensions are <strong>fully custom Design Compatible!</strong><br/>
Please check the picture and see how our extensions find their right balance according to your specific store design!<br/>
*Only compatible with Extension design properly made, therefore extension design compatible with the standard structure for the CSS (Cascading Style Sheet).</p>
<h2 style="text-align: justify; color:#e46b00;">Main Features:</h2>
<ul style="text-align: justify;">
<li><strong>1. Shopping Points</strong><br/>
Allow your customers to get points according to your directive for the value of your products and shopping cart. You can also freely enable or disable the function.</li><br/>
<li><strong>2. Referral Points</strong><br/>
Allow your customers to get points for friends' referral. They will be reward with points if the friends register to the website and/or if the friends buy on your store. The quantity of point can freely be decided by you according to your directive, and you can freely decide with function to enable, enable both or disable both.</li><br/>
<li><strong>3. Social Networks Share Points</strong><br/>
Allow your customers to get points for sharing on Social network information about your product or your website!<br/>
Every social Network are available and you can freely decide which social network you would like to enable and which not.<br/>
Moreover the amount of points is freely decided by you, and you can enable or disable the whole function.</li><br/>
<li><strong>4. Product Review Points</strong><br/>
For any product approved review that your customer does will reward him points! You can enable or disable the function and you can freely decide the amount of point to be rewarded.</li><br/>
<li><strong>5. Points expiration</strong><br/>
The module allow you to freely decide if the points have to be use within a certain date or if the points never expire</li><br/>
<li><strong>6. Variable Points Calculation</strong><br/>
It is up to you to fully decide how the point are calculated and given to your customer according to your specific needs.</li><br/>
<li><strong>7. Already Localized for 6 Languages!*</strong><br/>
All the front end experience is already localized for English, Italian, German, French, Spanish and Japanese!<br/>
*Some language are automatically translated.</li><br/>
<li><strong>8. Store view Specific.</strong><br/>
The module allows you to have specific rules for store view, for website or for Global granting you the opportunity to have a variety of personalization according to all your needs.</li><br/>
<li><strong>9. Single and Multi-Currency compatible.</strong><br/>
The points engine and the point value is fully compatible either you have only one or many currency on your store.<br/>
You can set the value of your points based on your base currency and have it automatically converted according to the currency use by your clients, so regardless the currency the client is using you will always have accurate points and discounts.</li><br/>
<li><strong>10. Specific Custom Rules</strong><br/>
This function is awesome for campaign and special deals! The extension allows you to freely create all your custom rules for every single product, category of products, class of products and much more. You have access to may product attribute to decide specifically how to create the rules. For instance you can reward additional points if they buy products in stock, or new product etc.<br/>
Furthermore you can have also specific rules for the Shopping Cart and decide to give additional point if your clients use a specific payment method, payment method, according to the size of the order etc. For instance your clients can get extra point if they pay with PayPal, or if they ship with UPS or if their shopping cart includes a certain amount of products and much more!<br/>
The possibilities and sales advantages given to you are really without limits!</li><br/>
<li><strong>11. Points and Coupons Selection.</strong><br/>
The Extension let you freely decide if you would like to use coupon discount together or disable the coupon when the points are applied.</li><br/>
<li><strong>12. Full report and tracking of given points</strong><br/>
You can easily access statistic on how the points are given, who of your client has more points or how they are spending their points.<br/>
You will always have an accurate overview on how the system is improving your sales!</li><br/>
</ul><br/>
<p style="text-align: justify;">These are only a few of the great advantages offer by this extension; <strong>discover all the functions and possibilities in which this wonderful extension will improve your store sales!</strong></p>
<p><em><strong>Arcanum Dev.</strong></em></p>

HTML;
		return $html;
	}
}