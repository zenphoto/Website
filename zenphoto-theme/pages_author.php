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
    	<h2><?php printZenpageItemsBreadcrumb('',''); ?>
    	<?php if($_zp_current_zenpage_page->getTitlelink() == 'contributors') { ?>
    		<?php printPageTitle(); ?>
    	<?php } ?>
    	</h2> 
    	<?php if($_zp_current_zenpage_page->getTitlelink() != 'contributors') { ?>
    		<h3 class="entrytitle"><?php printPageTitle(); zp_printAuthorStatusIcon(); ?>
    	<?php } ?>
    	<?php 
    	if(strtolower($_zp_current_zenpage_page->getTitle()) != strtolower($_zp_current_zenpage_page->getTitlelink())) {
    		?>
    		<em>(<?php echo $_zp_current_zenpage_page->getTitlelink(); ?>)</em>
    		<?php
    	}
    	?></h3>
    	<?php if($_zp_current_zenpage_page->getTitlelink() != 'contributors') { ?>
				<div class="entrymeta">
					<?php zp_printAuthorStatusRanks(); ?>
				</div>
  		<?php } ?>
  		<ol id="toc" class="table_of_content_list"></ol> 
  		<div class="entrybody">
				<?php printPageContent(); ?>
				<?php 
				if($_zp_current_zenpage_page->getTitlelink() == 'contributors') { 
					zp_printAuthorList('all',false);
				} else { ?>
					<?php zp_printAuthorContributions('author_'.$_zp_current_zenpage_page->getTitlelink(),'albums'); ?>
					<?php zp_printAuthorContributions('author_'.$_zp_current_zenpage_page->getTitlelink(),'news','extensions'); ?>
					<?php zp_printAuthorContributions('author_'.$_zp_current_zenpage_page->getTitlelink(),'news','user-guide'); ?>
  		 	<?php } ?>
  		 	
  		 	<?php printCodeblock(1); ?>
  		</div>
		

<?php include('footer.php'); ?>
