<?php include('header.php'); ?>

<div id="sidebar">
	<?php printSearchForm();	?>
	<?php
	$parent = $_zp_current_zenpage_page->getParentid();
	$subpages = $_zp_current_zenpage_page->getPages();
	//echo '<pre>'; print_r($parents).'<br />'; print_r($subpages); echo '</pre>';
	if(!is_null($parent) || $subpages) {
	?>

		<?php //printPageMenu('omit-top','','','','active','',1); ?>

	<?php } ?>
	<?php 
		if($_zp_current_zenpage_page->getTitlelink() == 'advertise') {
			zp_printSponsorAvailability(); 
		}
	?>
	<?php zp_printSidebarBoxes(); ?>
	<hr />
    
</div><!-- sidebar end -->

<div id="content">
    	<h2><?php printZenpageItemsBreadcrumb('',' » '); ?><?php printPageTitle(); ?>
    	<?php 
    	if($_zp_current_zenpage_page->getTitle() != $_zp_current_zenpage_page->getTitlelink()) {
    		?>
    		<em>(<?php echo $_zp_current_zenpage_page->getTitlelink(); ?>)</em>
    		<?php
    	}
    	?>
    	</h2> 
    	<div class="table_of_content_list" style="margin-top: -15px">
    	<?php
    	if($_zp_current_zenpage_page->hastag('zp_team-member')) {
  				echo "<h3>Zenphoto team member</h3>";
  			} else {
  				echo "<h3>Zenphoto contributor</h3>";
  			}
  		?>
    	<ul>
  		<?php
  			if($_zp_current_zenpage_page->hastag('zp_chief-developer')) {
  				echo "<li>Chief developer</li>";
  			}
  			if($_zp_current_zenpage_page->hastag('zp_forum-moderator')) {
  				echo "<li>Forum moderator</li>";
  			}
  			?>
  		 	</ul>
  		 </div>
  		<div class="entrybody">
  			<span id="entrybody">
  		 	<?php printPageContent(); ?>
  		 	<h3>Contributions</h3>
  		 	<h4>Themes</h4>
  		 	<?php 
  		 		zp_printAuthorContributions('author_'.$_zp_current_zenpage_page->getTitlelink(),'albums');
  		 	?>
  		 	<h4>Extensions</h4>
  		 	<?php 
  		 		zp_printAuthorContributions('author_'.$_zp_current_zenpage_page->getTitlelink(),'news');
  		 	?>
  		 	<?php printCodeblock(1); ?>
  		 	</span>
  		</div>
		

<?php include('footer.php'); ?>
