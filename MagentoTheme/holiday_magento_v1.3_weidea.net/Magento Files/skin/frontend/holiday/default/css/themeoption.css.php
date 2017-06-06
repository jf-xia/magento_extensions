<?php
define('MAGENTO_ROOT', (dirname(__FILE__).'/../../../../..'));
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
require_once $mageFilename;

umask(0);
if ( empty($_GET['store']) ) {
    $_GET['store'] = '';
}
Mage::app( $_GET['store'] );

header("Content-type: text/css; charset: UTF-8");
?>




/* Start main Color */

.main,
.header.fixed,
.toptital,
.fancy.product-view,
.page-empty,
#fancybox-outer,
.customized .best_theme,
.products-grid li .actions1 {  background: #<?php echo Mage::helper("ExtraConfig")->themeOptions('main_color') ?>; }


.tabs li.active a,
.tabs li a:hover {  border-bottom-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('main_color') ?>; }

/* End main Color */


/* Start primary Color */

.default-container #nav li a:hover,
.default-container #nav li a.over,
.default-container #nav li ul li a:hover,
.default-container #nav li ul li a.over,
.default-container #nav li.level0.active a.level-top,
.top-block h2,
.top-block span,
.quick-view span.icon-eye-open,
.quick-view:hover,
.product-name a:hover,
.footer a:hover,
.page-title h1, .page-title h2,
.currency_detail a:hover,
.currency_icon.selected,
.language_detail a:hover,
.sort_icon.selected,
.sort_detail a:hover,
.breadcrumbs li strong,
.products-list .desc .link-learn,
.products-list .product-name a:hover,
.block-layered-nav dt,
.block-layered-nav #narrow-by-list dd ol li a:hover,
#sidenav li a:hover,
.block-viewed li a:hover,
.block-poll .block-subtitle,
.block-compare li .product-name a:hover,
.old-price .price,
.product-essential h1,
.customized .best_theme h4,
.tabs li.active a,
.tabs li a:hover,
.block-related .block-subtitle a,
.product-view .box-reviews dt a,
.onepagecheckout_datafields .op_block_title,
.gift-messages-form h4,
.sub-title,
.opc .buttons-set p.required,
.block-progress dt.complete,
#opc-review .buttons-set p a,
.checkout-progress li.active,
.multiple-checkout .col2-set h2.legend,
.multiple-checkout h3, .multiple-checkout h4,
.multiple-checkout .box h2,
#opc-login .col2-set .col-2 p,
#opc-login li.control label,
#opc-login .col2-set .col-2 p,
.fieldset .legend,
p.required,
.dashboard .welcome-msg p strong,
.box-account .box-head h2,
.block-account .block-content li.current,
.block-account .block-content li a:hover,
.dashboard .box-content a,
.dashboard .box-tags .tags strong, .dashboard .box-tags .tags ul, .dashboard .box-tags .tags ul li,
.block-wishlist .link-cart,
.block-reorder .block-content li.item:hover .product-name a,
.show_icon.selected,
.show_detail a:hover,
.pager .pages .current,
.pager .pages li a:hover,
.link-print,
.order-info .current,
.order-items h2,
.addresses-list h2,
.addresses-list a,
.show_icon.selected,
.show_detail a:hover,
.my-tag-edit strong,
.compare-table .btn-remove:hover span,
.sf-menu a:hover,
.advanced-search-summary strong,
.products-grid li:hover .price-box .price,
div.menu.act a,
div.menu.active a,
div.menu .parentMenu a:hover,
div.wp-custom-menu-popup a.itemMenuName:hover,
div.wp-custom-menu-popup a.actParent,
div.wp-custom-menu-popup a.act,
div.wp-custom-menu-popup .itemSubMenu a.itemMenuName:hover,
.menu_customlinks li a:hover{  color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('primary_color') ?>; }

.products-grid .actions .add-to-links a:hover,
.products-grid .actions button.btn-cart:hover,
.ias_trigger a:hover,
.follow_button a.btn_follow,
.header .top-links a:hover,
.btn-remove:hover, .btn-edit:hover,
.header .shopping_bg .actions button.button:hover,
.grid-mode-active,
.list:hover,
.list-mode-active,
.grid:hover,
button.button,
.products-list .add-to-links li a:hover,
#sidenav li a.show-cat:hover,
#sidenav li a.active,
.customized .best_theme a span,
.product-view .product-shop .add-to-links li a:hover,
.detail-carousel .flex-direction-nav a:hover,
#related-slider .flex-direction-nav a:hover,
#upsell-slider .flex-direction-nav a:hover,
.btn-remove2:hover,
.cart-table td.a-center.last a.cartedit:hover, .cart-table td.a-center.last a.link-wishlist:hover,
.cart-table .btn-update:hover, .cart-table .btn-empty:hover,
#crosssell-slider .flex-direction-nav a:hover,
.close_la:hover,
.buttons-set .back-link a,
.error-msg,
.toggleMenu.active,
.opc .active .step-title,
.opc .active .step-title .number,
.block-progress dt.complete span.progress_font,
.dashboard .box-reviews .number,
.dashboard .box-tags .number,
.pager .pages a.next:hover,
.pager .pages a.previous:hover,
.compare-table .btn-remove:hover,
.error-msg,
.success-msg,
.note-msg,
.notice-msg,
.header .form-search button.button:hover,
div.alert,
.bx-wrapper .bx-next:hover,
.bx-wrapper .bx-prev:hover,
.banner .container a,
.scrollup:hover {  background-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('primary_color') ?>; }

.pager .pages a.next:hover,
.pager .pages a.previous:hover,
.btn-remove:hover, .btn-edit:hover,
.btn-remove2:hover,
#sidenav li a.show-cat:hover,
#sidenav li a.active,
.products-grid .actions button.btn-cart:hover,
.products-grid .actions .add-to-links a:hover,
.header .shopping_bg .actions button.button:hover,
.cart-table td.a-center.last a.cartedit:hover, .cart-table td.a-center.last a.link-wishlist:hover,
.cart-table .btn-update:hover, .cart-table .btn-empty:hover,
.detail-carousel .flex-direction-nav a:hover,
#related-slider .flex-direction-nav a:hover,
#upsell-slider .flex-direction-nav a:hover,
#crosssell-slider .flex-direction-nav a:hover,
.ias_trigger a:hover,
.toggleMenu.active,
.detail-carousel .flex-direction-nav a:hover,
.pager .pages a.next:hover,
.pager .pages a.previous:hover,
.error-msg,
.success-msg,
.note-msg,
.notice-msg,
.block .block-title strong span,
.scrollup:hover {  border-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('primary_color') ?>; }
 
.tabs li.active a,
.tabs li a:hover,
.sort_detail,
.show_detail,
.currency_detail,
.language_detail,
.default-container #nav li ul.shown-sub,
.checkout-progress li.active{ border-top-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('primary_color') ?>; }

.header .top-links a.user-login:hover,
.header .top-links a.user-logout:hover { border-left-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('primary_color') ?>; }

.products-list .add-to-links li a.link-wishlist:hover,
.product-view .product-shop .add-to-links li a:hover,
.grid-mode-active,
.grid:hover { border-right-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('primary_color') ?>; }

.sort_detail .sort_detail_arrow,
.show_detail .show_detail_arrow,
.currency_box .currency_arrow,
.language_box .language_arrow,
div.menu.active span.errow,
div.wp-custom-menu-popup{ border-bottom-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('primary_color') ?>; }

/* End primary Color */


/* Start Secondary Color */

.currency_box .currency_pan,
.language_box .language_pan,
.header .top-links a,
.currency_detail,
.language_detail,
.btn-remove,
.btn-edit,
.header .shopping_bg .actions button.button,
.products-grid .actions button.btn-cart,
.products-grid .actions .add-to-links a,
.ias_trigger a,
.sorter .view-mode,
.sort_box .sort_pan,
.products-list .add-to-links,
#sidenav li a.show-cat,
#sidenav li a:hover,
.block-layered-nav #narrow-by-list dd ol li a:hover,
.block-viewed li a:hover,
.block-compare li .product-name a:hover,
.customized,
.product-view .product-shop .add-to-links,
.detail-carousel .flex-direction-nav a,
#related-slider .flex-direction-nav a,
#upsell-slider .flex-direction-nav a,
.default-container #nav li ul li a.over,
.default-container #nav li ul li a:hover,
.btn-remove2,
.cart-table td.a-center.last a.cartedit, .cart-table td.a-center.last a.link-wishlist,
.cart-table .btn-update, .cart-table .btn-empty,
#crosssell-slider .flex-direction-nav a,
.sp-methods .form-list,
.close_la,
.toggleMenu,
.gift-messages-form,
.opc .step-title,
.block-account .block-content li a:hover,
.block-reorder .block-content li.item:hover,
.show_detail a:hover,
.show_box .show_pan,
.pager .pages a.next,
.pager .pages a.previous,
.show_box .show_pan,
.sf-menu a:hover,
.page-sitemap .sitemap,
DIV.ajaxcartpro_progress,
DIV.ajaxcartpro_progress1,
.sort_detail,
.show_detail,
input, select, textarea,
.scrollup,
.checkout-multishipping-shipping .box-sp-methods,
.header .form-search,
.header .form-search .search-autocomplete ul,
.bx-wrapper .bx-controls-direction a,
div.alert a,
div.wp-custom-menu-popup .itemSubMenu a.itemMenuName:hover,
.menu-block,
.menu_customlinks li a:hover,
div.wp-custom-menu-popup a.itemMenuName{  background-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('secondary_color') ?>; }

.currency_box .currency_pan,
.language_box .language_pan,
.btn-remove,
.btn-edit,
.header .shopping_bg .actions button.button,
.products-grid .actions button.btn-cart,
.products-grid .actions .add-to-links a,
.ias_trigger a,
.sort_box .sort_pan,
#sidenav li a.show-cat,
#sidenav li a:hover,
.block-layered-nav #narrow-by-list dd ol li a:hover,
.block-viewed li a:hover,
.block-compare li .product-name a:hover,
.detail-carousel .flex-direction-nav a,
#related-slider .flex-direction-nav a,
#upsell-slider .flex-direction-nav a,
.default-container #nav li ul li a.over,
.default-container #nav li ul li a:hover,
.btn-remove2,
.cart-table td.a-center.last a.cartedit, .cart-table td.a-center.last a.link-wishlist,
.cart-table .btn-update, .cart-table .btn-empty,
#crosssell-slider .flex-direction-nav a,
.close_la,
.toggleMenu,
.gift-messages-form,
.block-account .block-content li a:hover,
.block-reorder .block-content li.item:hover,
.show_detail a:hover,
.show_box .show_pan,
.pager .pages a.next,
.pager .pages a.previous,
.show_box .show_pan,
.show_detail a:hover,
.sort_detail a:hover,
.sf-menu a:hover,
.product-view .product-shop .add-to-links,
.sorter .view-mode,
.products-list .add-to-links,
.header .top-links,
input, select, textarea,
.sp-methods .form-list,
.checkout-multishipping-shipping .box-sp-methods,
.category-products .toolbar .pager,
.category-products .toolbar,
.header .quick-access,
.pager .limiter,
.my-account .pager,
.cart-table td.last,
.my-account #wishlist-table td.last,
.my-account #my-orders-table td.last,
.category-products .toolbar .sorter .limiter,
.header a.logo, .logo,
.tabs li,
#product-review-table td,
.header .form-search,
.header .form-search button.button,
.header .form-search .search-autocomplete ul,
div.alert a,
.scrollup,
div.wp-custom-menu-popup .itemSubMenu a.itemMenuName:hover,
.menu_customlinks li a:hover{  border-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('secondary_color') ?>; }

.checkout-progress li { border-top-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('secondary_color') ?>; }

.header .top-links a.user-login,
.sort_detail,
.show_detail,
.currency_detail,
.language_detail,
.header .top-links a.user-logout { border-left-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('secondary_color') ?>; }

.products-list .add-to-links li a.link-wishlist,
.product-view .product-shop .add-to-links li a,
.sort_detail,
.show_detail,
.currency_detail,
.language_detail,
.grid  { border-right-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('secondary_color') ?>; }

.sort_detail,
.show_detail,
.currency_detail,
.language_detail { border-bottom-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('secondary_color') ?>; }

/* End Secondary Color */


/* Start button color */

button.button,
.buttons-set .back-link a,
.customized .best_theme a span,
.follow_button a.btn_follow,
div.alert a {  background-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('buttonaerrow_color') ?>; }

/* End button color */


/*  button hover color */

button.button:hover,
.buttons-set .back-link a:hover,
.customized .best_theme a span:hover,
.follow_button a.btn_follow:hover,
div.alert a:hover {  background-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('buttonhover_color') ?>; }

/* End button hover color */


/* border color */

.new-arrow1,
.footer-logo,
.products-grid li .actions,
.page-title,
.bestseller-product .bestseller-grid li,
.block .actions,
.block-wishlist .block-content li.item,
.block-progress dd,
.bestseller-product .block-title,
.block .block-title,
.block,
.cart .cart-collaterals,
.cart .discount, .cart .shipping,
.cart .shipping .sp-methods,
.cart .crosssell h2,
.product-essential h1,
.cart .discount h2, .cart .shipping h2, .cart .totals h2,
.col1-layout .product-view .custom,
.product-view .product-shop .short-description,
.product-options,
.block-related .block-title,
.product-collateral h2,
.block-progress dt,
#opc-login h3,
.sp-methods dt,
.gift-messages h3,
.gift-messages-form h4,
.gift-messages-form .item .details .product-name,
.custom2-views.cloudzoom .product-image-zoom,
.detail-carousel.cloudzoom #carousel2 .flex-viewport li,
.detail-carousel .flex-direction-nav,
.detail-carousel,
.detail-carousel #slider .flex-viewport li.flex-active-slide,
.detail-carousel #carousel1 .flex-viewport li.flex-active-slide,
.detail-carousel.cloudzoom #slider .flex-viewport li,
#slider1 .flex-viewport,
.buttons-set,
.multiple-checkout .col2-set, .multiple-checkout .col3-set,
.fieldset .legend,
.account-login .content h2,
.box-account .box-head,
.dashboard .box .box-title,
.dashboard .box-info h4,
.order-info-box h2,
.addresses-list h2,
.addresses-list h3,
.product-view .product-shop .add-to-box,
.tabs,
.data-table tbody th,
.data-table td,
.data-table thead th,
.toptital,
.block-cart .block-subtitle,
.mini-products-list li,
.header .shopping_bg .subtotal .actions,
.block-cart .block-content .mini-products-list li.last,
.onepagecheckout_datafields .op_block_title,
.onepagecheckout_datafields,
.order-products-table tfoot td,
.order-products-table th,
.order-products-table tbody th, .order-products-table tbody td,
.custom2-views .noimage,
div.wp-custom-menu-popup a.itemMenuName{  border-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('border_color') ?>; }

.tabs li.active a,
.tabs li a:hover {  border-left-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('border_color') ?>; }

.tabs li.active a,
.tabs li a:hover {  border-right-color:  #<?php echo Mage::helper("ExtraConfig")->themeOptions('border_color') ?>; }

.data-table tbody th,
.data-table tbody td,
.cart-table td.a-center,
.order-products-table tbody td,
.cart-table td.a-right {  background: #<?php echo Mage::helper("ExtraConfig")->themeOptions('table_color') ?>; }

/* End border color */


/* Start Menu */

#nav ul,
div.wp-custom-menu-popup{  background-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('menu_background') ?>; }

.default-container #nav a,
div.menu a,
div.wp-custom-menu-popup a.itemMenuName,
.menu_customlinks li a,
.menu_customlinks li{ color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('menu_fonts_color') ?>; }

.default-container #nav li a.over,
.default-container #nav li a:hover,
.default-container #nav li ul li a:hover,
.default-container #nav li ul li a.over,
.default-container #nav li.level0.active a.level-top,
div.menu.act a,
div.menu.active a,
div.menu .parentMenu a:hover,
div.wp-custom-menu-popup a.itemMenuName:hover,
div.wp-custom-menu-popup a.actParent,
div.wp-custom-menu-popup a.act,
div.wp-custom-menu-popup .itemSubMenu a.itemMenuName:hover,
.menu_customlinks li a:hover{ color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('menu_fontshover_color') ?>; }

.default-container #nav li ul li a.over,
.default-container #nav li ul li a:hover,
div.wp-custom-menu-popup .itemSubMenu a.itemMenuName:hover,
.menu_customlinks li a:hover,
div.wp-custom-menu-popup a.itemMenuName{ background-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('menu_fontshover_backgroundcolor') ?>; }

.default-container #nav li ul li a.over,
.default-container #nav li ul li a:hover,
div.wp-custom-menu-popup .itemSubMenu a.itemMenuName:hover,
.menu_customlinks li a:hover{ border-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('menu_fontshover_backgroundcolor') ?>; }

<?php $menufont = Mage::helper("ExtraConfig")->themeOptions('menu_fonts'); ?>
<?php if(isset($menufont) && $menufont != null && $menufont != '--select--')   {  ?>
        
            #nav a,
            div.menu a,
            div.wp-custom-menu-popup a.itemMenuName,
            div.wp-custom-menu-popup .itemSubMenu a.itemMenuName,
            .menu_customlinks li a,
            .menu_customlinks li,
            .menu-block h2,
            .menu-block p{font-family: '<?php echo $menufont; ?>'!important;}
            
<?php } ?>
        
/* End Menu */


/* Start Sidebar */

.block,
.bestseller-product {  background-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('sidebar_background_color') ?>; }

<?php $sidebartitlefont = Mage::helper("ExtraConfig")->themeOptions('sidebar_title_fonts'); ?>
<?php   if(isset($sidebartitlefont) && $sidebartitlefont != null && $sidebartitlefont != '--select--')   {  ?>
        
            .block .block-title strong, .bestseller-product h2 {font-family: '<?php echo $sidebartitlefont; ?>';}
            
<?php } ?>

.block .block-title strong,
.bestseller-product h2 {  color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('sidebar_title_fonts_color') ?>; }

.block-layered-nav #narrow-by-list dd ol li a:hover,
.block-blog .menu-recent ul li a:hover, .block-blog .menu-categories ul li a:hover, .block-blog .menu-tags ul li a:hover,
#sidenav li a:hover,
.block-viewed li a:hover,
.block-account .block-content li a:hover,
.sf-menu a:hover,
.block-compare li .product-name a:hover,
.block-reorder .block-content li.item:hover {  background-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('sidebar_linkhover_bg_color') ?>; }

.block-layered-nav #narrow-by-list dd ol li a:hover,
.block-blog .menu-recent ul li a:hover, .block-blog .menu-categories ul li a:hover, .block-blog .menu-tags ul li a:hover,
#sidenav li a:hover,
.block-viewed li a:hover,
.block-account .block-content li a:hover,
.sf-menu a:hover,
.block-compare li .product-name a:hover,
.block-reorder .block-content li.item:hover {  border-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('sidebar_linkhover_bg_color') ?>; }

.block-layered-nav #narrow-by-list dd ol li a,
.block-category-nav .block-content dd ol li a,
.bestseller-product .bestseller-grid .product-name a,
.bestseller-product .bestseller-grid .old-price .price,
.bestseller-product .bestseller-grid .special-price .price,
.bestseller-product .bestseller-grid .regular-price .price,
.block .block-content .product-name a,
.block-poll .block-subtitle,
.block-poll label,
.block-wishlist .block-subtitle,
.mini-products-list .product-details .price-box .old-price .price,
.mini-products-list .product-details .price-box .special-price .price,
.mini-products-list .product-details .price-box .regular-price .price,
.block-wishlist .link-cart,
.block-wishlist .actions a,
.block-subscribe label,
.block-account .block-content li a,
.block-reorder .block-subtitle,
.block .actions a,
.block-layered-nav .currently .label,
.block-layered-nav .currently .value,
.block .empty,
.block-blog .menu-recent UL LI a,
.block-blog .menu-categories UL LI a,
.block-blog .menu-tags UL LI a,
#sidenav li a,
.sf-menu a,
.block .block-content .tags-list li a,
.block-progress dt,
.block-progress dd.complete address {  color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('sidebar_fonts_color') ?>; }

.bestseller-product .bestseller-grid li,
.block .actions,
.block-wishlist .block-content li.item,
.block-progress dd,
.bestseller-product .block-title,
.block .block-title,
.block,
.block-progress dt {  border-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('sidebar_seperator_color') ?>; }

/* End Sidebar */


/* Start Footer */

.footer li a {  color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('footer_link_font_color') ?>; }

.footer a:hover {  color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('footer_linkhover_font_color') ?>; }

.footer li {  border-color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('footer_link_font_color') ?>; }

/* End Footer */


/* product list */

.products-grid .product-image,
.products-list .product-image,
.detail-carousel .product-image,
.mini-products-list .product-image,
.data-table td a img,
.detail-carousel #slider .flex-viewport li,
.detail-carousel #carousel1 .flex-viewport li,
.detail-carousel.cloudzoom #carousel2 .flex-viewport li,
#slider1 .flex-viewport li,
.detail-carousel .slides li,
.noimage li.item,
.vertical-noimage li.item,
.horizontal-noimage li.item,
.default-noimage li.item,
.fancy.product-view .product-image {  background: #<?php echo Mage::helper("ExtraConfig")->themeOptions('product_bg') ?>; }

/* end product list */


/* start Background Option */

<?php
$background_color = Mage::helper("ExtraConfig")->themeOptions('background_color');
$background_pattern = Mage::helper("ExtraConfig")->themeOptions('background_pattern');
$background_image = Mage::helper("ExtraConfig")->themeOptions('background_image');
?>

 <?php  if(isset($background_color) && $background_color != null) {  ?>
		
            body
                {
                    background-color:#<?php echo Mage::helper("ExtraConfig")->themeOptions('background_color') ?>;
                }
<?php	}  elseif(isset($background_pattern) && $background_pattern != null){ ?>
        
            body
                {
                    background-image: url(<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'pattern/'.Mage::helper("ExtraConfig")->themeOptions('background_pattern') ?>);
                }    
<?php   } elseif(isset($background_image) && $background_image != null){ ?>

                body            
                {
                        background-image: url(<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'custom/image/'.Mage::helper("ExtraConfig")->themeOptions('background_image') ?>);
                        background-attachment: <?php echo Mage::helper("ExtraConfig")->themeOptions('bg_attachment') ?>;
                         background-position: <?php echo Mage::helper("ExtraConfig")->themeOptions('bg_position_y') ?> <?php echo Mage::helper("ExtraConfig")->themeOptions('bg_position_x') ?>;
                        background-repeat: <?php echo Mage::helper("ExtraConfig")->themeOptions('bg_repeat') ?>;
			
			<?php if (Mage::helper("ExtraConfig")->themeOptions('bg_attachment') == 'fixed')
				{
			?>
				background-size: 100%;
			<?php	} ?>
			
                }    
        
<?php   } else { } ?>

/* End Background Option */

/* start grayscale */
<?php $grayscale = Mage::helper("ExtraConfig")->themeOptions('grayscale'); ?>
<?php if($grayscale == '0'){ ?>
	
		.products-grid li.item:hover .product-image{filter: none;-webkit-filter: grayscale(0);}
		.products-list li.item:hover .product-image{filter: none;-webkit-filter: grayscale(0);}
	
<?php } ?>

/* end grayscale */


/* Theme Fonts Settings */

<?php
$titlefont = Mage::helper("ExtraConfig")->themeOptions('titlefont');
$bodyfont = Mage::helper("ExtraConfig")->themeOptions('bodyfont');
?>

<?php if(isset($titlefont) && $titlefont != null && $titlefont != '--select--')  {  ?>
           
            .page-title h1, .page-title h2, .product-essential h1 {font-family: '<?php echo $titlefont; ?>';}
        
<?php } ?>
	
<?php if(isset($bodyfont) && $bodyfont != null && $bodyfont != '--select--')  {  ?>
    
            body,
            a,
            input, select, textarea,
            button.button,
            x-small,
            small,
            .top-block h2,
            .special-price .price,
            .regular-price .price,
            .price-box .price,
            .ias_trigger a,
            strong,
            .quick-view,
            .block .block-title strong,
            .breadcrumbs li strong,
            .block-layered-nav dt,
            .customized .best_theme a span,
            .cart .totals .checkout-types button.btn-checkout span,
            .cart .totals td,
            .cart .totals td span.price,
            #opc-login .col2-set .col-1 h4,
            .gift-messages h3,
            .gift-messages-form h4,
            .gift-messages-form .item .details .product-name,
            .gift-messages-form .item .number,
            .sort_box .sort_pan span,
            .show_box .show_pan span,
            .fieldset .legend,
            .buttons-set .back-link a,
            .checkout-progress li,
            .checkout-multishipping-shipping .box-sp-methods .pointer p,
            .messages, .messages ul,
            .multiple-checkout .place-order .grand-total big,
            .box-account .box-head h2,
            .block-account .block-content li strong,
            .ratings strong,
            .link-print span,
            .order-items h2,
            .addresses-list h2,
            .product-review .product-name,
            .ratings-table th,
            .product-review dt,
            .detail-block h3,
            #nav li li a span,
            .customized h3,
            div.alert a,
            div.alert p strong,
            div.wp-custom-menu-popup a.itemMenuName,
            div.wp-custom-menu-popup .itemSubMenu a.itemMenuName,
            .menu-block h2{font-family: '<?php echo $bodyfont; ?>';}
        
<?php } ?>	

.page-title h1,
.page-title h2,
.product-essential h1 {  color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('titlefont_color') ?>; }

.page-title h1,
.page-title h2,
.product-essential h1  {  font-size: <?php echo Mage::helper("ExtraConfig")->themeOptions('titlefont_size') ?>px; }

body,
a,
.header .form-search input.input-text,
.header .form-search button.button,
.compare-table .btn-remove span,
.products-grid .actions button.btn-cart,
.products-list .add-to-links .separator,
.product-view .product-shop .add-to-links li .separator,
.cart-table .btn-update,
.cart-table .btn-empty,
.multiple-checkout .gift-messages h3,
.quick-view,
.header .shopping_bg .actions button.button,
input, select, textarea {  color: #<?php echo Mage::helper("ExtraConfig")->themeOptions('bodyfont_color') ?>; }

body,
a,
input, select, textarea,
x-small,
small,
.default-container #nav li ul li a,
.sort_box .sort_pan span,
.show_box .show_pan span,
.pager .pages .current,
.breadcrumbs li a,
.block-wishlist .special-price .price,
.old-price .price,
.block-wishlist .regular-price .price,
.block-wishlist .old-price .price,
button.button,
.price-from .price-label,
.price-to .price-label,
.minimal-price .price-label,
.mini-products-list .product-details strong,
.cart .totals .checkout-types button.btn-checkout span,
.cart .totals td span.price,
.checkout-progress li,
.multiple-checkout h2,
.fieldset .legend,
.fieldset strong,
.multiple-checkout h3,
.multiple-checkout h4,
.box-content address,
.sp-methods label,
.col-1 address,
.col-2 address,
.col-3 address,
.product-name,
.buttons-set p.required,
.dashboard .welcome-msg p strong,
.dashboard .box-recent .box-head h2,
.dashboard .box-recent .box-head a,
.block-account .block-content li.current strong,
.dashboard .box-info .box-head h2,
.dashboard .box .box-title a,
.dashboard .box-reviews .box-head h2,
.dashboard .box-reviews .box-head a,
.dashboard .box-tags .box-head h2,
.dashboard .box-tags .box-head a,
.ratings strong,
.dashboard .box-tags .tags strong,
.dashboard .box-tags .tags ul li a,
.pager,
.link-print span,
.order-info dt,
.order-info .current,
.order-date,
.order-info-box h2,
.order-items h2,
.data-table tfoot strong,
.addresses-list h2,
.tag-customer-index .my-account p,
.addresses-list h3,
.product-review h3,
.ratings-table th,
.product-review dt,
.product-review .product-img-box .label,
.oauth-customer-token-index .my-account p,
.downloadable-customer-products .my-account p,
.customized .best_theme a span,
.product-view .product-shop .availability span,
.product-view .product-options dt label,
.product-view .product-shop .add-to-cart label,
.breadcrumbs li strong,
.tabs li a,
.product-view .box-tags .note,
.product-view .box-reviews dt span,
#product-review-table tbody th,
.multiple-checkout .col3-set .col-1 strong,
.checkout-multishipping-success .multiple-checkout p,
.cart-empty p,
.ias_trigger a,
.registered-users p.required,
.customer-account-forgotpassword .fieldset p,
DIV.ajaxcartpro_progress div,
DIV.ajaxcartpro_progress1 div,
.footer li a,
.sub-title,
.advanced-search-summary strong,
div.wp-custom-menu-popup .itemSubMenu a.itemMenuName{  font-size: <?php echo Mage::helper("ExtraConfig")->themeOptions('bodyfont_size') ?>px; }

/* End Theme Fonts Settings */


/* sticky header */

<?php $sticky_header = Mage::helper("ExtraConfig")->themeOptions('sticky_header'); ?>
<?php if($sticky_header == '0' || Mage::helper("ExtraConfig")->is_mobile() == true) { ?>
    .header.fixed {display: none;}
<?php } ?>

/* sticky header */