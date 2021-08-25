<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">
<h2><?php printAlbumTitle(); ?></h2>
<?php
printAlbumDesc();
zporgSponsors::printAds(); ?>
<br clear="all" />
		
<?php include('footer.php'); ?>