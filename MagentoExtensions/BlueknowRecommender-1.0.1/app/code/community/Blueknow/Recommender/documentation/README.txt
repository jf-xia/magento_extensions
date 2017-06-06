/**
 * Blueknow Recommender for Magento.
 * 
 * @copyright	Copyright (c) 2009-2011 Blueknow, S.L. (http://www.blueknow.com)
 * @license		GNU General Public License
 * @author		<a href="mailto:santi.ameller@blueknow.com">Santiago Ameller</a>
 * 
 */

About the module
================

	* Name:          Blueknow Recommender for Magento eCommerce platform (v1.4.x).
	* Description:   Personalized product recommendations for your eCommerce: cross-sell and up-sell.
	* Version:       1.0.1.

	NOTE: this module has been successfully tested on Magento 1.4.x. If your eCommerce runs under an older version, please contact with us at support@blueknow.com.
	
Important notes
===============

	* Once add-on is installed, your clients won't get personalized recommendations until at least 24 hours after.
	* If some language you need is not supported, create it! Your new language file should be under %MAGENTO_HOME%/app/locale/<your_locale>/Blueknow_Recommender.csv.
	  Take other language file as example to define your new one.
	* If you modified an older version of this add-on, be careful not to loose your changes (widget templates, and so on).
	* If you are using cache in your eCommerce, some functionalities added by this add-on may not work. Refresh it from Magento's Admin Panel.
	* Errors? Problems? Bugs? Contributions? Let us know at support@blueknow.com.

How to install
==============

	To install Blueknow Recommender in your Magento distribution:

		(Remote) Use Magento Connect from Magento's Admin Panel: System > Magento Connect > Magento Connect Manager.
		(Local)  Use PEAR: %MAGENTO_HOME%/pear install Blueknow_Recommender-1.0.0.tgz.
		
	Modify the shopping cart template of your Magento installation:
	
		1. Affected file: app/design/frontend/default/defaul/template/checkout/cart.phtml.
		2. Code to be added (feel free with its location): <?php echo $this->getChildHtml('blueknow.cart.recommender.widget') ?>
		
	After installing the extension, you will need to configure it from your Magento's Admin Panel: System > Configuration > Services > Blueknow Recommender.

	Optionally, you can review the default recommendations widget template provided for changing it according your needs:
		
		1. Template for product detail cross-sell recommendations widget .... %MAGENTO_HOME%/app/design/frontend/default/default/template/blueknow/product/recommender_widget.phtml.
		2. Template for shopping cart up-sell recommendations widget ........ %MAGENTO_HOME%/app/design/frontend/default/default/template/blueknow/cart/recommender_widget.phtml.
	
	Once configuration is complete, Blueknow Tracker will start tracking behavioral events (visits, purchases...) and 24 hours later your clients 
	will be receiving personalized recommendations.
	
	If you need more information about this module, how to obtain a BK number for your eCommerce for free, or about the service in general, please visit our 
	public site at http://www.blueknow.com or contact with our Customer support department (support@blueknow.com).

Change log
==========

Version 1.0.1
-------------

	Bug fixing version.

	+ [MAGPLUGIN-1] - Track cached images to improve loading time of the widget.
	+ [MAGPLUGIN-2] - Grouped, configurable and bundled products are not rendered when price is retrieved.
	+ [MAGPLUGIN-3] - Unexpected page loading whenever widget is rendered.
	+ [MAGPLUGIN-4] - Quotes in product name causes a JavaScript error.

Version 1.0.0
-------------

	Initial version.

	+ Tracking of users' products views.
	+ Tracking of discontinued products.
	+ Tracking of users' logins.
	+ Tracking of users' purchases.
	+ Tracking of discontinued products after a purchase.
	+ Cross-sell recommendations in product detail page.
	+ Up-sell recommendations in shopping cart page.