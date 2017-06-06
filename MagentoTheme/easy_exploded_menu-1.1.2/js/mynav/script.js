$jq=jQuery.noConflict();
$jq(function() {
	$jq('ul.level0').each(function(index) {
 //   	alert(index + ': ' + $jq(this).text());
//	alert($jq(this));
		var leng = $jq($jq(this).children('li')).length;
		$jq(this).wrap('<div class="nav-inner">');
		$jq(this).parent().wrap('<div class="shadow-bottom">');
		$jq(this).parent().parent().wrap('<div class="red-border">');
		$jq(this).parent().parent().parent().wrap('<div class="shadow-round-right">');
		$jq(this).parent().parent().parent().parent().wrap('<div class="shadow-round-left">');
		$jq(this).parent().parent().parent().parent().parent().wrap('<div class="menu children'+leng+'">');		
  	});
});