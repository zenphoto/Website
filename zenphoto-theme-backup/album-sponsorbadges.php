<?php include('header.php'); ?>
<div id="sidebar">
<?php printSearchForm();	?>
	<hr />
	<?php printAlbumDesc(true); ?>
	<hr />
<?php zp_printSidebarBoxes(); ?>


</div>
<div id="content">

<h2><?php printAlbumTitle(); ?></h2>
<?php 
	zp_specialdownloads('sponsorbadges')
?>
		<br clear="all" />


<?php include('footer.php'); ?>