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
		

	$cons = file('http://www.zenphoto.org/themes/zenphoto-theme/zenphoto-contributors.csv');
	if($cons) {
		foreach($cons as $con) {
			$c = explode('|',$con);
			//echo "<pre>";print_r($authors); echo "</pre>";
			if(in_array(trim($c[2]),$authors)) {
				echo '<li><strong>'.$c[2].'</strong></li>';
			} else {
				echo '<li>'.$c[2].'</li>';
			}
		}
		//echo "<pre>"; print_r($authors); echo "</pre>";
	} 
 ?>
</ol>
<?php include('footer.php'); ?>
