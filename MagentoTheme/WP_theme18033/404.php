<?php if($_GET['ajaxRequest']!=true){get_header();} ?>
<div id="content">
	<div class="error404 contentCol">404</div>
	<hgroup>
		<?php echo '<h2>Sorry!</h2>'; ?>
		<?php echo '<h3>Page Not Found</h3>'; ?>
	</hgroup>
	<?php echo '<p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>'; ?>
	<?php echo '<p>Please try using our search box below to look for information on the internet.</p>'; ?>
	<form method="get" id="searchform">
		<input type="text" class="searching animate" value="" name="s" /><input class="submit button animate" type="submit" value="SEARCH"/>
	</form>
</div>
