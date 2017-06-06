<?php
include "../../../wp-config.php";

if(!class_exists('SinaOAuth')){
	include dirname(__FILE__).'/sinaOAuth.php';
}

$to = new SinaOAuth($sina_consumer_key, $sina_consumer_secret);

	
$tok = $to->getRequestToken(get_option('home'));

$_SESSION["sina_oauth_token_secret"] = $tok['oauth_token_secret'];
if($_GET['callback_url']){
	$callback_url = $_GET['callback_url'];
}else{
	$callback_url = get_option('home');
}
$request_link = $to->getAuthorizeURL($tok['oauth_token'],true,$callback_url);

header('Location:'.$request_link);
?>
