<?php
include('header.php');
include('sidebar.php');
$obj = @$_zp_gallery_page;
?>
<div id="content">
<h1 class="pagetitle">Oops, sorry!</h1>
	<div id="content-error">

		<div class="errorbox">
			<?php
			echo gettext("What you are requesting cannot be found. Maybe you want to try a search?");
			if (isset($album)) {
				echo '<br />' . gettext("Album") . ': ' . $album;
			}
			if (isset($image)) {
				echo '<br />' . gettext("Image") . ': ' . $image;
			}
			if (isset($obj)) {
				echo '<br />' . gettext("Page") . ': ' . substr(basename($obj), 0, -4);
			}
			?>
		</div>

	</div>


	<?php include('footer.php'); ?>
