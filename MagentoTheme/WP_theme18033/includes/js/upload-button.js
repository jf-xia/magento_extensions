jQuery(document).ready(function() {
	
	jQuery('#tz_portfolio_thumb_button').click(function() {
		
		window.send_to_editor = function(html) 
		
		{
			imgurl = jQuery('img',html).attr('src');
			jQuery('#tz_portfolio_thumb').val(imgurl);
			tb_remove();
		}
	 
	 
		tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
		return false;
		
	});
 
	jQuery('#tz_portfolio_image_button').click(function() {
		
		window.send_to_editor = function(html) 
		
		{
			imgurl = jQuery('img',html).attr('src');
			jQuery('#tz_portfolio_image').val(imgurl);
			tb_remove();
		}
	 
	 
		tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
		return false;
		
	});
	 
	jQuery('#tz_portfolio_image_button2').click(function() {
		
		window.send_to_editor = function(html) 
		{
			imgurl = jQuery('img',html).attr('src');
			jQuery('#tz_portfolio_image2').val(imgurl);
			tb_remove();
		}
	
		tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
		return false;
		
	});
	
	jQuery('#tz_portfolio_image_button3').click(function() {
		
		window.send_to_editor = function(html) 
		{
			imgurl = jQuery('img',html).attr('src');
			jQuery('#tz_portfolio_image3').val(imgurl);
			tb_remove();
		}
	
		tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
		return false;
		
	});
	
	jQuery('#tz_portfolio_image_button4').click(function() {
		
		window.send_to_editor = function(html) 
		{
			imgurl = jQuery('img',html).attr('src');
			jQuery('#tz_portfolio_image4').val(imgurl);
			tb_remove();
		}
	
		tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
		return false;
		
	});
	
	jQuery('#tz_portfolio_image_button5').click(function() {
		
		window.send_to_editor = function(html) 
		{
			imgurl = jQuery('img',html).attr('src');
			jQuery('#tz_portfolio_image5').val(imgurl);
			tb_remove();
		}
	
		tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
		return false;
		
	});

});
