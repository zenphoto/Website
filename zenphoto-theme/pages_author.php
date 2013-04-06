<?php include('header.php'); ?>

<div id="sidebar">
	<?php printSearchForm();	
	$parent = $_zp_current_zenpage_page->getParentid();
	$subpages = $_zp_current_zenpage_page->getPages();
	$subpagecount = ' <small>('.count($subpages).')</small>';
	if(!is_null($parent) || $subpages) {
		//echo '<hr />';
		//printPageMenu('omit-top','','','','active','',1); 
	}
	zp_printThemeStatusIconList(); 
	zp_printExtensionStatusIconList();
	//echo '<pre>'; print_r($parents).'<br />'; print_r($subpages); echo '</pre>';
	zp_printSidebarBoxes(); 
	?>
	<hr />
</div><!-- sidebar end -->
<div id="content">
	<?php 
		// cheating a bit on the parent as we do want the separate pages for team, former team and contributors but not the "database" page "all-contributors"!
		switch($_zp_current_zenpage_page) {
			case $_zp_current_zenpage_page->hasTag('zp_team-member'):
				?>
				<h2><a href="<?php echo html_encode(getPageLinkURL('about-us')); ?>">About us</a> | <a href="<?php echo html_encode(getPageLinkURL('zenphoto-team')); ?>">Zenphoto team</a></h2>
				<?php
				break;
			case $_zp_current_zenpage_page->hasTag('zp_team-member-former'):
				?>
				<h2><a href="<?php echo html_encode(getPageLinkURL('about-us')); ?>">About us</a> | <a href="<?php echo html_encode(getPageLinkURL('former-team-members')); ?>">Former team members</a></h2>
				<?php
				break;
			case $_zp_current_zenpage_page->hasTag('zp_contributor'):
				?>
				<h2><a href="<?php echo html_encode(getPageLinkURL('about-us')); ?>">About us</a> | <a href="<?php echo html_encode(getPageLinkURL('contributors')); ?>">Contributors</a></h2>
				<?php
				break;
		}
		switch($_zp_current_zenpage_page) {
			case $_zp_current_zenpage_page->hasTag('zp_team-member'):
			case $_zp_current_zenpage_page->hasTag('zp_team-member-former'):
			case $_zp_current_zenpage_page->hasTag('zp_contributor'):
				?>
    		<h3 class="entrytitle"><?php printPageTitle(); ?>
    			<?php if(strtolower($_zp_current_zenpage_page->getTitle()) != strtolower($_zp_current_zenpage_page->getTitlelink())) { 
    				?>
    				<em>(<?php echo $_zp_current_zenpage_page->getTitlelink(); ?>)</em>
    				<?php
    		 }
    		 zp_printAuthorStatusIcon();
    		 ?>
    		</h3>
    		<?php
				break;
			default:
			 	?>
			 	<h2 class="entrytitle">
    			<?php printPageTitle(); echo $subpagecount; ?>
    		</h2>
    		<?php
				break;
			 }
			 if($parent) { ?>
				<div class="entrymeta">
					<?php zp_printAuthorStatusRanks(); ?>
				</div>
  		<?php } ?>
  		<!-- <ol id="toc" class="table_of_content_list"></ol> -->
  		<div class="entrybody">
  			<?php
  			$data = $_zp_current_zenpage_page->getCustomData();
  			$explode = explode('|',$data);
  			if(count($explode) != 0) {  			
  				switch($explode[0]) {
  					case 'gravatar':
  						echo zp_getAuthorSocialImage(trim($explode[1]),'gravatar',trim($explode[2]));
  						break;
  					case 'google':
  					case 'facebook':
  						echo zp_getAuthorSocialImage(trim($explode[1]),trim($explode[0]));
  						break;
  				}
  			}
				printPageContent(); 
				if($_zp_current_zenpage_page->getTitlelink() != 'all-contributors') { 
					zp_printAuthorContributions('author_'.$_zp_current_zenpage_page->getTitlelink(),'news','release');
					zp_printAuthorContributions('author_'.$_zp_current_zenpage_page->getTitlelink(),'albums');
					zp_printAuthorContributions('author_'.$_zp_current_zenpage_page->getTitlelink(),'news','extensions');
					zp_printAuthorContributions('author_'.$_zp_current_zenpage_page->getTitlelink(),'news','user-guide');
				} else { 
					zp_printAuthorList('all',false,$subpages);
  		 	} 
  		 ?>
  		</div>
		

<?php include('footer.php'); ?>
