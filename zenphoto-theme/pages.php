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
  		 	<?php printPageContent(); ?>
  		 	<?php printCodeblock(1); ?>
  		 	</span>
  		</div>

		

<?php include('footer.php'); ?>
