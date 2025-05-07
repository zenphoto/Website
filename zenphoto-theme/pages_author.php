<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">
	<?php
	// cheating a bit on the parent as we do want the separate pages for team, former team and contributors but not the "database" page "all-contributors"!
	switch ($_zp_current_zenpage_page) {
		case $_zp_current_zenpage_page->hasTag('zp_team-member'):
			?>
	<div class="breadcrumb"><a href="<?php echo html_encode(getPageURL('about-us')); ?>">About us</a> | <a href="<?php echo html_encode(getPageURL('zenphoto-team')); ?>">Zenphoto team</a></div>
			<?php
			break;
		case $_zp_current_zenpage_page->hasTag('zp_team-member-former'):
			?>
		<div class="breadcrumb"><a href="<?php echo html_encode(getPageURL('about-us')); ?>">About us</a> | <a href="<?php echo html_encode(getPageURL('former-team-members')); ?>">Former team members</a></div>
			<?php
			break;
		case $_zp_current_zenpage_page->hasTag('zp_contributor'):
			?>
			<div class="breadcrumb"><a href="<?php echo html_encode(getPageURL('about-us')); ?>">About us</a> | <a href="<?php echo html_encode(getPageURL('contributors')); ?>">Contributors</a></div>
			<?php
			break;
	}
	switch ($_zp_current_zenpage_page) {
		case $_zp_current_zenpage_page->hasTag('zp_team-member'):
		case $_zp_current_zenpage_page->hasTag('zp_team-member-former'):
		case $_zp_current_zenpage_page->hasTag('zp_contributor'):
			?>
			<h1 class="entrytitle"><?php printPageTitle(); ?>
				<?php if (strtolower($_zp_current_zenpage_page->getTitle()) != strtolower($_zp_current_zenpage_page->getName())) {
					?>
					<em>(<?php echo $_zp_current_zenpage_page->getName(); ?>)</em>
					<?php
				}
				zporg::printAuthorStatusIcon();
				?>
			</h1>
			<?php
			break;
		default:
			?>
			<h1 class="entrytitle">
				<?php printPageTitle(); ?>
			</h1>
			<?php
			break;
	}
	if ($parent) {
		?>
		<div class="entrymeta">
		<?php zporg::printAuthorStatusRanks(); ?>
		</div>
<?php } ?>
	<!-- <ol id="toc" class="table_of_content_list"></ol> -->
	<div class="entrybody">
		<?php
		/*$data = strval($_zp_current_zenpage_page->getCustomData());
		$explode = explode('|', $data);
		if (count($explode) != 0) {
			switch ($explode[0]) {
				case 'gravatar':
					echo zporg::getAuthorSocialImage(trim($explode[1]), 'gravatar', trim($explode[2]));
					break;
				case 'google':
				case 'facebook':
					echo zporg::getAuthorSocialImage(trim($explode[1]), trim($explode[0]));
					break;
			}
		} */
		printPageContent();
		if ($_zp_current_zenpage_page->getName() != 'all-contributors') {
			zporg::printAuthorContributions('author_' . $_zp_current_zenpage_page->getName(), 'news', 'release');
			zporg::printAuthorContributions('author_' . $_zp_current_zenpage_page->getName(), 'albums');
			zporg::printAuthorContributions('author_' . $_zp_current_zenpage_page->getName(), 'news', 'extensions');
			zporg::printAuthorContributions('author_' . $_zp_current_zenpage_page->getName(), 'news', 'user-guide');
			if(!zporg::hasContentMacro($_zp_current_zenpage_page->get("content"), 'donate')) {
				echo zporg::getDonateCallToActionHTML();
			}
		} else {
			zporg::printAuthorList('all', false, $subpages);
		}
		?>
	</div>


	<?php include('footer.php'); ?>
