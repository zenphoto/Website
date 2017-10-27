<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">
			<?php if($parent) { ?>
    		<h2><?php printZenpageItemsBreadcrumb('',''); ?></h2> 
    		<h3 class="entrytitle"><?php printPageTitle(); ?></h3>
    	<?php } else { ?>
    		<h2 class="entrytitle"><?php printPageTitle(); ?></h2>
    	<?php } ?>
    	<!-- <ol id="toc" class="table_of_content_list"></ol> -->
  		<div class="entrybody">
  			<span id="entrybody">
  		 	<?php 
				printPageContent(); 
				zp_printSponsorCategories();
				$adtos_page = new ZenpagePage('advertising-terms-of-service');
				if($adtos_page->loaded) {
					?>
					<p>Please review our <a href="<?php echo html_encode($adtos_page->getLink()); ?>"><strong>Advertising Terms Of Service</strong></a> before contacting us.</p>
					<?php
				}
				printPageExtraContent(); 
				?>
  		 	</span>
  		</div>

		

<?php include('footer.php'); ?>
