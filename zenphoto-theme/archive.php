<?php include('header.php'); ?>

<div id="sidebar">
	<?php printSearchForm();	?>
	<hr />
	<?php zp_printSidebarBoxes(); ?>
	<hr />
	<h2 class="latestadditions">Popular tags</h2>
	<?php
		if(function_exists('printAllTagsFromZenpage')) {
			?>
			<?php printAllTagsFromZenpage('news','','taglist',false,true,0.5,2,5,50); ?>
			<?php
		} else {
			printAllTagsAs('cloud','taglist','abc',TRUE,TRUE,2,50,10,NULL,0.8);
		}
  ?>
   <?php //printAllTagsAs('cloud','taglist','abc',TRUE,TRUE,2,50,10,NULL,0.8); ?>
</div><!-- sidebar end -->

<div id="content">
    	<h2>Site archive</h2>
    	<?php if(function_exists("printNewsArchive")) { ?>
			<h3 class="entrytitle">News archive</h3>
			<?php printNewsArchive("archive"); ?>
			<hr />
			<?php } ?>
			<h3 class="entrytitle">Gallery archive</h3>
			<?php printAllDates(); ?>
			<hr />
			

<?php include('footer.php'); ?>
