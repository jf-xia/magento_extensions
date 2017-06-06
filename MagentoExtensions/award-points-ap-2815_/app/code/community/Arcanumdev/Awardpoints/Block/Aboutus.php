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
 
class Arcanumdev_Arcanumdev_Block_Aboutus
	extends Mage_Adminhtml_Block_Abstract
	implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$html = <<<HTML
<div style="background:url('http://arcanumdev.wafunotamago.com/media/Products/ADLogo.gif') no-repeat scroll 14px 14px #EAF0EE;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 105px;">

<h2 style="text-align: justify; color: #e46b00;">Development Wizards at your Service!</h2>
<p style="text-align: justify;">Arcanum Dev. is a company which specializes in offering a great variety of services and solution for your IT needs.<br />We firmly believe on creating solutions not only for resolve the problems or the special necessity you may have with Magento, or any other software, but mostly for offer you a solution with no second thoughts, a solution in which you can always rely constantly and unconditionally.</p>
<h2 style="text-align: justify; color: #e46b00;">Our Services :</h2>
<ul style="text-align: justify;">
<li><strong>Magento extension Development</strong><br /> For any needs you may have regarding your personalization of Magento do not hesitate to contact us for an analysis and a quote. In our Arcanum our wizards are continuously improving their skills by challenging always more demanding projects.<br /> We are absolutely confident to be able to offer you with any solution for any of your problems or necessity.</li>
<br />
<li><strong>Software Development</strong><br /> For any problem you may have in your website or office there is always a solution, and in Arcanum Dev. we are real professional with an absolute confidence on our skill and knowledge, and we can offer you outstanding solutions to any problem with the best performing and most light applications.</li>
<br />
<li><strong>Graphic Design</strong><br /> Featuring the most correct and appropriate looks for your business or website is as much important as the software or people that run it.<br /> For these reason Graphic Design solutions are absolute necessity for any winning business.<br /> Arcanum Dev. feature the greatest graphic designer experience from all over the world and fields, Publishing, Videogame, Web, Multimedia and much more!.<br /> We are here always ready to listen to any request, and offer you the best solution for your needs.</li>
<br />
<li><strong>Database Design</strong><br /> The foundation of any Software is based on the reliability and performance of its database. Not paying the correct attention to the Database design will certainly result in devastating problems in the future.<br /> The Database has always to be specifically designed for each needs, more accurate is its design and more reliable and fast will be the software who will access it.<br /> We saw and help many companies who encounter this problem, and we did collaborate with many new winning businesses on the correct design for their database. This for small business as well as important enterprises<br /> Feel free to contact us for a personalize analysis of your database or for a new database design specifically according to your needs.</li>
<br />
<li><strong>Logical Solution</strong><br /> Database Design, Software Development and Graphic Design are very important for a winning business, however even with the greatest professional in each section nothing good can come out without the proper Logical Solution. The starting Logical analysis before any IT production is what really makes the difference on its success.<br /> Before starting any IT project every problems, necessity and options has to be predicted even before design any part of it.<br /> If you are starting your business and you really want to be certain of your success feel free to contact us and together we will analyze your software on the most rewarding way.<br /> If you already have your IT solutions but are not properly compatible with your necessity feel free to contact us and we will analyze every aspect of it for grant you with the most performing redesign plan.</li>
<br /> 
</ul>
<h2 style="text-align: justify; color: #e46b00;">Our Policy :</h2>
<ul style="text-align: justify;">
<li><strong>Integrity and Morality</strong><br /> Our company is based in Tokyo Japan and our team is form by multi ethnic people, however we all share the same lifestyle and professional ideologies. We strongly believe in our products and we always want to be absolutely certain of your satisfaction.<br /> Sometimes the choice we make may have a negative effect on our self and we take full responsibility for those choices, such as not encoding our sources, by overworking for implement the necessary modification while meeting the deadlines, by refusing projects when we don't agree with the customer policies or when we strongly believe that the solution requested by a customer will have a negative effects on his business.<br /> We make these choices because, for us, the satisfaction of our clients is more important than our income, the gratification of our clients&rsquo; happiness is more rewarding than our paycheck.<br /> We do business in the same way as we live our life, by fully supporting who trust us, by never let down a person or a company who rely on us, by never charging additional "hidden" cost to our products, by never creating solutions in which we don't agree just for get paid and by never forcing our clients in choices that may have negative effects on them.<br /> We are professional, we work and live with absolute Integrity and Morality and never feeling any regrets for the choice we make.</li>
<br />
<li><strong>NO IonCube, or any other PHP Encoders</strong><br /> It is indeed important for us to preserve and protect our code and our intellectual propriety, however it is greatly more important to be certain to grant you a solution which will never reduce your store performance.<br /> Using a PHP encoder such as IonCube will drastically reduce the performance of your store. First at all you will need to have the decoder installed on your PHP server and you need to be certain your provider will allow such installation otherwise you will be unable to use the encoded software you bought unless you change provider; moreover the company who sold you the software for this king of incompatibility, will certainly refuse you a refund. Differently, If you manage to install it, on your server the single encoded script "may" run faster however the loader will read and process every single PHP file before redirect it to the PHP server this for check if the file requested needs decoding or not. Magento, for instance, feature an outstanding modular structure and on your server may run simultaneously over 400 PHP files which do not require any decoding but are being slow down by the interpretation of the loader/decoder, and "maybe" one or two files needing decoding. These files may run faster, but the overhaul performance is greatly compromised.<br /> It is our first priority the client satisfaction; for all this reason we did decide to don't use any PHP encoder, for be certain to provide you with a solution which will <strong>NEVER</strong>, in any way, reduce the performance of your company.<br /> Furthermore by buying our products you are the absolute owner of it, such as it always should be.<br /> In most of the cases, companies who encodes their PHP files does it because they are not confident with the code in it by being full of "workaround function" or unstable behavior, or simply because the scripts involved are not fully their own.<br /> At Arcanum Dev, differently, we are very proud of products which are all open source ready for you to study and personalize, if our scripts, beside from resolving your problems, may also improve your knowledge and skills then we are even more proud to have contributed a bit to your professional growth.<br /> Therefore if you have the knowledge and skills to read and edit the scripts included in the software you bought it is perfectly fine and rightful for you to do so and to apply any modifications you feel necessary for improve it and to better suit the product YOU BOUGHT to your store needs. Arcanum Dev believe in this as an absolute right for any customer. Naturally we will not provide any free support on a third part modified script.<br /> Differently, if you don't possess the knowledge and skills for personalize the script but you wish to apply some modification feel free to contact us directly for an analysis and an estimate on applying those modification or for see if the modification may be implemented on a future release.</li>
<br />
<li><strong>NO hidden Price or hidden fees</strong><br />The Price you see is the real and only price for our product!<br /> Many times you may be surfing the net and get interested on a product with a very interesting price, however when you visit the developer company web site and their product page you get welcome by a very disappointing surprise.<br /> For instance, in the majority of our competitors&rsquo; web sites you will find: Installation fees being 3 times the value of the product, license and features limitations and offered as option with huge price gaps and much more.<br /> At the end for have a "final product" with all the features you need on your store you will pay a price far greatly higher compare to ours.<br /> And this is not all, when you finally download and receive the software, you will realize that is fully encoded and you will be force to contact again the company and pay high fees for any minimal modification or, even, for simply have their support.<br /> At Arcanum Dev we deeply despise such behavior.<br /> In our website you will NEVER be fool by these despicable methods which only take advantage of the customer trust, moreover you will never be force to pay any additional fees for what should be free.<br /> Our prices, at first sight, may seems expensive, this simply because the price you see is the real and only price for the product without any lark mirrors. Furthermore our products includes much more potentiality and features compare to our competitors and all our solutions are open source, without any restriction on editing and improving your script for better suit your company needs. From the first time you contact us you will always be treated as you deserve, with absolute and outstanding respect, transparency and professionalism.</li>
<br /> 
</ul>
<h2 style="text-align: justify; color: #e46b00;">"There are no shortcuts!"</h2>
<p style="text-align: justify;">On Any Software and IT solution development you never have to choose a shortcut or developing a solution which will "go around" the problem.<br /> If you do so it will absolutely, certainly and unconditionally create problems in the future, and will drastically ruin your business on the long run. No exception.<br /> In Arcanum dev. we strictly believe in this policy, every Magento extension, basic or advance, any software, graphic and any solution we offer you are strictly develop according to you company fondation, structure and aims for granting you with a solution worthy of its name for your precious business and for a reliable future. Some solutions may require more time for development, some less, but in each one, you can be ensured, there is no shortcut!</p>
<p>For any of your need feel free to <a href="mailto:arcanumdev@wafunotamago.com">contact us.</a></p>
<p><strong>WE ARE</strong> always at your service.<br /> <em><strong>Arcanum Dev.</strong></em></p>
<p style="text-align: right;"><em>"Silicon units never fails, unless are following wrong directives made by carbon units"</em><br /> <em>Amon Antiga</em></p>

HTML;
		return $html;
	}
}