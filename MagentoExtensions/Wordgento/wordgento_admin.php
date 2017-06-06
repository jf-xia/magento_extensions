	<?php
		if($_POST['wordgento_hidden'] == 'Y') {
			//Form data sent
			$wordgento_mage = strtolower($_POST['wordgento_magepath']);
			update_option('wordgento_magepath', $wordgento_mage);

			$wordgento_theme = strtolower($_POST['wordgento_theme']);
			update_option('wordgento_theme', $wordgento_theme);
			
			$wordgento_store = strtolower($_POST['wordgento_store']);
			update_option('wordgento_store', $wordgento_store);
			?>
			<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
			<?php
		} else {
			//Normal page display
			$wordgento_mage = strtolower(get_option('wordgento_magepath'));
			$wordgento_theme = strtolower(get_option('wordgento_theme'));
			$wordgento_store = strtolower(get_option('wordgento_store'));
		}
	?>
		
        <div class="wrap">
			<?php    echo "<h2>" . __( 'Wordgento Options', 'wordgento_trdom' ) . "</h2>"; ?>

			<form name="wordgento_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="wordgento_hidden" value="Y">
				
				<table class="form-table"><tbody>
                
                <tr valign="top">
                <th scope="row"><label for="wordgento_magepath">Path to Magento:</label></th>
                <td> <input type="text" name="wordgento_magepath" value="<?php echo $wordgento_mage; ?>" size="20">
                <span class="description">E.g: If your Magento is at http://www.domain.com/shop then enter <strong>/shop</strong>, if it is a root installation of Magento, simply enter <strong>/</strong></span></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><label for="wordgento_theme">Magento Theme Name:</label></th>
                <td> <input type="text" name="wordgento_theme" value="<?php echo $wordgento_theme; ?>" size="20">
                <span class="description">E.g: default, modern, blank, etc</span></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><label for="wordgento_store">Magento Store Code:</label></th>
                <td> <input type="text" name="wordgento_store" value="<?php echo $wordgento_store; ?>" size="20">
                <span class="description">E.g: If you only have one store on your Magento installation, leave this as default - otherwise you will need to enter the store code for the store you want to get blocks from.</span></td>
                </tr>
                
                </tbody></table>		

				<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options', 'wordgento_trdom' ) ?>" />
				</p>
			</form>
            
            <h3>Usage Instructions</h3>
            <p>Using this plugin, there are a variety of blocks you can bring out from your Magento installation. Enter the path to your Magento installation in the box above, and then enter the theme you are using into the second box.</p>
            <p>Once you have done this, you are ready to put the code snippets into your Wordpress template to bring out some Magento blocks.</p>
            <p>The codes to use are as follows:</p>
            <?php $plugname = "wordgento"; ?>
            <style type="text/css">
            	code {
                    padding:5px 10px; border:1px dashed #ccc
                }
                dd { 
                	margin:10px 10px 15px;
                }
            </style>
            <dl>
            	<dt><strong>CSS/JS</strong> <em>(This is usually in the head.phtml file of your Magento theme.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('cssjs'); ?&gt;</code></dd>
                
                <dt><strong>Includes</strong> <em>(This is usually in the head.phtml file of your Magento theme.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('inc'); ?&gt;</code></dd>
                
                <dt><strong>Welcome Message</strong> <em>(This is usually in the header.phtml file of your Magento theme.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('welcome'); ?&gt;</code></dd>
                
                <dt><strong>Logo</strong> <em>(This is usually in the header.phtml file of your Magento theme.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('logo'); ?&gt;</code></dd>
                
                <dt><strong>URL</strong> <em>(This is usually in the header.phtml file of your Magento theme, around the logo.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('url'); ?&gt;</code></dd>
                
                <dt><strong>Top Links</strong> <em>(This is usually in the header.phtml file of your Magento theme.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('toplinks'); ?&gt;</code></dd>
                
                <dt><strong>Search</strong> <em>(This is usually in topBar.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('search'); ?&gt;</code></dd>
                
                <dt><strong>Top Menu</strong> <em>(This is the main menu, requires the css/js to be loaded for dropdowns.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('topmenu'); ?&gt;</code></dd>
                
                <dt><strong>Wishlist</strong> <em>(This is usually in the left sidebar.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('wishlist'); ?&gt;</code></dd>
                
                <dt><strong>Recently Viewed</strong> <em>(This is usually in the left sidebar.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('recently_viewed'); ?&gt;</code></dd>
                
                <dt><strong>Compare</strong> <em>(This is usually in the left sidebar.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('compare'); ?&gt;</code></dd>
                
                <dt><strong>Sidebar Cart</strong> <em>(This is usually in the left sidebar.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('sidecart'); ?&gt;</code></dd>
                
                <dt><strong>Newsletter</strong> <em>(This is usually in the footer.)</em></dt>
                <dd><code>&lt;?php echo <?php echo $plugname; ?>('newsletter'); ?&gt;</code></dd>
                
            </dl>
            
            <h3>Support</h3>
            <p>For help and support, please visit the <a href="http://www.tristarwebdesign.co.uk/wordpress-plugins/wordgento/" title="Wordgento" target="_blank">Wordgento Plugin homepage</a>.</p>
            
            <h3>Donate</h3>
            <p>We have big plans for Wordgento, but it takes time and resources to develop plugins like this. If you are feeling generous, we would truly appreciate a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ARGY32BH344K2" title="Donate to Wordgento" target="_blank">donation</a>.</p>
            
            </div>
            
		</div>