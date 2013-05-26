<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

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
