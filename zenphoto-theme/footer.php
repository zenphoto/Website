<!-- Close Page -->
<!-- begin footer -->
</div>

<div id="footer">
	<div class="column-l">
		<div class="infobox">
			<h3>RSS feeds!</h3>
			<ul class="rsslinks" data-track-content>
				<li><a href="<?php echo WEBPATH; ?>/index.php?mergedrss" title="RSS all news">All news</a> (incl. all	below except forum)</li>
				<li><a href="<?php echo WEBPATH; ?>/index.php?rss=news" title="RSS all general news">All general news</a> (except themes, showcase, screenshots)</li>
				<li><a href="<?php echo WEBPATH; ?>/index.php?rss=news&amp;category=news" title="RSS New releases and announcements">New releases and announcements</a></li>
				<li><a href="<?php echo WEBPATH; ?>/index.php?rss=news&amp;category=user-guide" title="RSS User guide">User guide</a></li>
				<li><a href="<?php echo WEBPATH; ?>/index.php?rss=news&amp;category=extensions" title="RSS Extensions">Extensions</a></li>
				<li><a href="<?php echo WEBPATH; ?>/index.php?rss=gallery&amp;folder=screenshots" title="RSS Screenshots	and screencasts">Screenshots	and screencasts</a></li>
				<li><a href="<?php echo WEBPATH; ?>/index.php?rss=gallery&amp;folder=theme&amp;albumsmode&amp;sortorder=latest" title="RSS Themes">Themes</a></li>
				<li><a href="<?php echo WEBPATH; ?>/index.php?rss=gallery&amp;albumname=showcase" title="RSS Showcase">Showcase</a></li>
				<li><a href="http://forum.zenphoto.org/discussions/feed.rss">Forum</a></li>
			</ul>
			<br />
		</div>
	</div>

	<div class="column-m">
		<div class="infobox">
			<h3>Join the Google Group!</h3>
				<ul class="downloadlinks">
					<li><a href="https://groups.google.com/group/zenphoto-announce">zenphoto-announce</a><br />Release announcements and security alerts only (no support)
					<br /><strong>Subscribe right here:</strong><br />

					<form	action="https://groups.google.com/group/zenphoto-announce/boxsubscribe" id="googlegroups">
					<small>Email:</small>
					<input type="text" name="email" size="20"><br />
					<input type="submit" name="sub" value="Subscribe">
					</form><br />
				</li>
			</ul>
			<p>Website powered by Zenphoto<br />
					Forum powered by <a href="https://open.vanillaforums.com">Vanilla</a>
			</p>
		</div>
	</div>

	<div class="column-r">
		<div class="infobox">
			<h3>Information</h3>
			<ul class="downloadlinks">
				<li><?php printPageURL('About us', 'about-us','','',NULL); ?></li>
				<li><?php printPageURL('Contact', 'contact','','',NULL); ?></li>
				<li><?php printPageURL('Get involved', 'get-involved','','',NULL); ?></li>
				<li><?php printPageURL('Paid support', 'paid-support','','',NULL); ?></li>
				<li><?php printPageURL('Advertise', 'advertise','','',NULL); ?></li>
				<li><a href="https://github.com/zenphoto/zenphoto/issues?state=open" title="Bugtracker (GitHub)">Bugtracker <small>(GitHub)</small></a></li>
			</ul>
			<br />
			<h3>Legal stuff</h3>
			<ul class="downloadlinks">
				<li><?php printPageURL('Legal', 'legal','','',NULL); ?></li>
				<li><?php printPageURL('Forum Terms Of Service ', 'forum-terms-of-service','','',NULL); ?></li>
				<li><?php printPageURL('Data Privacy Policy', 'data-privacy-policy','','',NULL); ?></li>
			</ul>
		 </div>
	</div>
	<?php 
	if (class_exists('scriptlesssocialsharing')) {
		scriptlessSocialsharing::printProfileButtons('<h3>Follow us</h3>');
	}
	?>
<br style="clear:both" />
</div>
</div><!-- #container -->
<?php
zp_apply_filter('theme_body_close');
?>
</body>
</html>
