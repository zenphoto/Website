<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">
			<?php if($parent) { ?>
    		<div class="breadcrumb"><?php printZenpageItemsBreadcrumb('',''); ?></div> 
    		<h1 class="entrytitle"><?php printPageTitle(); ?></h1>
    	<?php } else { ?>
    		<h1 class="entrytitle"><?php printPageTitle(); ?></h1>
    	<?php } ?>
    	<!-- <ol id="toc" class="table_of_content_list"></ol> -->
  		<div class="entrybody">
  			<span id="entrybody">
  		 	<?php 
				printPageContent(); 
				printCodeblock(1); 
				if(!zporg::hasContentMacro($_zp_current_zenpage_page->get("content"), 'donate')) {
					echo zporg::getDonateCallToActionHTML();
				}
				?>
  		 	</span>
  		</div>

		

<?php include('footer.php'); ?>
