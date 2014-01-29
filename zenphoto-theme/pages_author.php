<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">
	<?php 
		// cheating a bit on the parent as we do want the separate pages for team, former team and contributors but not the "database" page "all-contributors"!
		switch($_zp_current_zenpage_page) {
			case $_zp_current_zenpage_page->hasTag('zp_team-member'):
				?>
				<h2><a href="<?php echo html_encode(getPageURL('about-us')); ?>">About us</a> | <a href="<?php echo html_encode(getPageURL('zenphoto-team')); ?>">Zenphoto team</a></h2>
				<?php
				break;
			case $_zp_current_zenpage_page->hasTag('zp_team-member-former'):
				?>
				<h2><a href="<?php echo html_encode(getPageURL('about-us')); ?>">About us</a> | <a href="<?php echo html_encode(getPageURL('former-team-members')); ?>">Former team members</a></h2>
				<?php
				break;
			case $_zp_current_zenpage_page->hasTag('zp_contributor'):
				?>
				<h2><a href="<?php echo html_encode(getPageURL('about-us')); ?>">About us</a> | <a href="<?php echo html_encode(getPageURL('contributors')); ?>">Contributors</a></h2>
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
    			<?php printPageTitle(); ?>
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
  			$data = sanitize($_zp_current_zenpage_page->getCustomData());
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
