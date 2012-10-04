<?php include('header.php'); ?>

<div id="sidebar">
	<?php printSearchForm();	?>
	<?php zp_printSidebarBoxes(); ?>
	<hr />
	<h2 class="latestadditions">Popular tags</h2>
	<?php
		if(function_exists('printAllTagsFromZenpage')) {
			?>
			<p><?php printAllTagsFromZenpage('news','','taglist',false,true,0.8,4,5,50); ?></p>
			<br style="clear:both" />
			<hr />
			<?php
		} else {
			printAllTagsAs('cloud','taglist','abc',TRUE,TRUE,2,50,10,NULL,0.8);
		}
  ?>
   <?php //printAllTagsAs('cloud','taglist','abc',TRUE,TRUE,2,50,10,NULL,0.8); ?>
   <br clear="all" />
</div><!-- sidebar end -->

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
