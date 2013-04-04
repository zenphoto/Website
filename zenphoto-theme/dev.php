<?php include('header.php'); ?>

<div id="sidebar">
	<h2>Note</h2>
	<p>This page is just meant for live testing of new functions or similar so it does not appear on the actual site's content.</p>
</div><!-- sidebar end -->

<div id="content">
    	<h2>Dev test page</h2>

	<ol>
	<?php
	$authors = zp_getSpecificTags('all',$tagmode='author');
/*	foreach($authors as $author) {
		echo '<li>'.$author;
		$more = zp_getMoreByThisAuthor($author);
		$count = count($more);
		echo ' ('.$count.')</li>';
	} */
		?>
	</ol>
	
<ol>
<?php
		
 ?>
</ol>
<?php include('footer.php'); ?>
