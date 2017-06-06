<?php

?>
<?php

class AddThis_SharingTool_Model_Source_Buttons
{
    public function toOptionArray()
    { 
    	$result = array();
    	
        $result[] = array('class'=>'buttons','value'=>'style_1','label'=>'&nbsp;&nbsp;
		<img src="http://cache.addthiscdn.com/icons/v1/thumbs/facebook.gif" style="vertical-align:middle"/>&nbsp;
		<img src="http://cache.addthiscdn.com/icons/v1/thumbs/twitter.gif" style="vertical-align:middle"/>&nbsp;
		<img src="http://cache.addthiscdn.com/icons/v1/thumbs/email.gif" style="vertical-align:middle"/>&nbsp;
		<img src="http://cache.addthiscdn.com/icons/v1/thumbs/google.gif" style="vertical-align:middle"/>&nbsp;
		<img src="http://cache.addthiscdn.com/icons/v1/thumbs/linkedin.gif" style="vertical-align:middle"/>&nbsp;
		<img src="http://cache.addthiscdn.com/icons/v1/thumbs/menu.gif" style="vertical-align:middle"/><br/><br/>');
        
		$result[] = array('value'=>'style_2','label'=>'&nbsp;&nbsp;<img src="http://cache.addthiscdn.com/downloads/plugins/magento/gtc-like-tweet-share.gif" style="vertical-align:middle"/><br/><br/>');
		
		$result[] = array('value'=>'style_3','label'=>'&nbsp;&nbsp;<img src="http://cache.addthiscdn.com/icons/v1/thumbs/32x32/facebook.png" style="vertical-align:middle"/>&nbsp;
	                <img src="http://cache.addthiscdn.com/icons/v1/thumbs/32x32/twitter.png" style="vertical-align:middle" />&nbsp;
	                <img src="http://cache.addthiscdn.com/icons/v1/thumbs/32x32/email.png" style="vertical-align:middle" />&nbsp;
		            <img src="http://cache.addthiscdn.com/icons/v1/thumbs/32x32/google.png" style="vertical-align:middle" />&nbsp;
		            <img src="http://cache.addthiscdn.com/icons/v1/thumbs/32x32/linkedin.png" style="vertical-align:middle" />&nbsp;
		            <img src="http://cache.addthiscdn.com/icons/v1/thumbs/32x32/addthis.png" style="vertical-align:middle" /><br/><br/>');
	   
	    $result[] = array('value'=>'style_4','label'=>'&nbsp;&nbsp;<img src="http://cache.addthiscdn.com/icons/v1/thumbs/menu.gif" style="vertical-align:middle"/>&nbsp;
    			   <label>Share</label>&nbsp;<img src="http://cache.addthiscdn.com/icons/v1/thumbs/facebook.gif" style="vertical-align:middle" />&nbsp;
    			   <img src="http://cache.addthiscdn.com/icons/v1/thumbs/myspace.gif" style="vertical-align:middle" />&nbsp;
    			   <img src="http://cache.addthiscdn.com/icons/v1/thumbs/google.gif" style="vertical-align:middle" />&nbsp;
                   <img src="http://cache.addthiscdn.com/icons/v1/thumbs/twitter.gif" style="vertical-align:middle" /><br/><br/>');		
		
	    $result[] = array('value'=>'style_5','label'=>'&nbsp;&nbsp;<img src="http://cache.addthiscdn.com/icons/v1/thumbs/menu.gif" style="vertical-align:middle"/>&nbsp;
    			   <label>Share</label><br/><br/>');		
	   
	   
	    $result[] = array('value'=>'style_6','label'=>'&nbsp;&nbsp;<img src="https://cache.addthiscdn.com/www/20111123101657/images/sharecount-horizontal.gif" style="vertical-align:middle"/><br/><br/>');	
	   
	    $result[] = array('value'=>'style_7','label'=>'&nbsp;&nbsp;<img src="https://cache.addthiscdn.com/www/20111123101657/images/sharecount-vertical.gif" style="vertical-align:middle"/><br/><br/>');
	   
	    $result[] = array('value'=>'style_8','label'=>'&nbsp;&nbsp;<img src="http://s7.addthis.com/static/btn/sm-plus.gif" style="vertical-align:middle"/><br/><br/>');
	   
	    $result[] = array('value'=>'style_9','label'=>'&nbsp;&nbsp;<b>Custom Button</b><style>#sharing_tool_button_style_button_setstyle_1{margin-left:6px;} .note{width:500px;}</style>');
	   
		return $result;
    }
}