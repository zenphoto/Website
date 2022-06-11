<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">
<h1 class="breadcrumb"><?php printAlbumTitle(); ?></h1>
<?php
printAlbumDesc();
zporgSponsors::printAds(); ?>
<br clear="all" />
		
<?php include('footer.php'); ?>