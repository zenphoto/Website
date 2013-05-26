<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<div id="content">
    	<h2>Oops, sorry!</h2>
    	<div id="content-error">

		<div class="errorbox">
 		<?php
		echo gettext("What you are requesting cannot be found. Maybe you want to try a search?");
		if (isset($album)) {
			echo '<br />'.gettext("Album").': '.sanitize($album);
		}
		if (isset($image)) {
			echo '<br />'.gettext("Image").': '.sanitize($image);
		}
		if (isset($obj)) {
			echo '<br />'.gettext("Page").': '.substr(basename($obj),0,-4);
		}
 		?>
		</div>

</div>
			

<?php include('footer.php'); ?>
