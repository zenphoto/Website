<br clear: all />
<!-- Close Page -->
<!-- begin footer -->

</div>
<div id="footer"><a name="stay-tuned"></a>
<div class="column-l">
	<div class="infobox">
		<h3>RSS feeds!</h3>
		<ul class="rsslinks">
			<li><a href="<?php echo WEBPATH; ?>/index.php?rss=news&amp;withimages">All news</a> (incl. all	below except forum)</li>
			<li><a href="<?php echo WEBPATH; ?>/index.php?rss=news&amp;category=news">New releases and announcements</a></li>
			<li><a href="<?php echo WEBPATH; ?>/index.php?rss=news&amp;category=user-guide">User	guide</a></li>
			<li><a href="<?php echo WEBPATH; ?>/index.php?rss=news&amp;category=extensions">Extensions</a></li>
			<li><a href="<?php echo WEBPATH; ?>/index.php?rss&amp;folder=screenshots">Screenshots	and screencasts</a></li>
			<!-- <li><a href="<?php echo WEBPATH; ?>/index.php?rss&amp;albumtitle=themes&amp;folder=theme">Themes</a></li> -->
			<li><a href="<?php echo WEBPATH; ?>/index.php?rss&folder=theme&amp;albumsmode&amp;sortorder=latest">Themes</a></li>
			<li><a href="<?php echo WEBPATH; ?>/index.php?rss&amp;albumname=showcase">Showcase</a></li>
			<li><a href="<?php echo WEBPATH; ?>/support/rss.php">Forum</a></li>
		</ul>
		<br />
		<h3>Social networks</h3>
		<ul class="sociallinks">
		 	<li class="link_twitter"><a href="http://twitter.com/#!/zenphotocms" title="Zenphoto on Twitter">Twitter</a></li>
			<li class="link_googleplus"><a href="https://plus.google.com/103649195484756812404" rel="publisher">Google+</a></li>
			<li class="link_github"><a href="https://github.com/zenphoto/zenphoto" title="Zenphoto on GitHub">GitHub</a></li>
			<li class="link_facebook"><a href="https://www.facebook.com/zenphotocms" title="Zenphoto on Facebook">Facebook</a></li>
			<li class="link_youtube"><a href="http://www.youtube.com/zenphotocms" title="Zenphoto on Youtube">Youtube</a></li>
		 </ul>
  </div>
</div>

<div class="column-m">
	<div class="infobox">
		<h3>Join the Google Groups!</h3>
			<ul class="downloadlinks">
				<li><a href="http://groups.google.com/group/zenphoto-announce">zenphoto-announce</a><br />Release announcements and security alerts only (no support)
				<br /><strong>Subscribe right here:</strong><br />

				<form	action="http://groups.google.com/group/zenphoto-announce/boxsubscribe" id="googlegroups">
				<small>Email:</small>
				<input type="text" name="email" size="20"><br />
				<input type="submit" name="sub" value="Subscribe">
				</form><br />
				</li>
				<li><a href="http://groups.google.com/group/zenphoto-translate">Zenphoto
				Translate</a><br />Support and discussion for translators only</li>
			</ul>
				<p>
		Website powered by Zenphoto<br />
		Forum powered by <a href="http://bbpress.org">bbpress</a>
		</p>
		</div>
		
</div>

<div class="column-r">
	<div class="infobox">
		<h3>Information</h3>
		<ul class="downloadlinks">
			<li><?php printPageLinkURL('About us', 'about-us','','',NULL); ?></li>
			<li><?php printPageLinkURL('Contact', 'contact','','',NULL); ?></li>
			<li><?php printPageLinkURL('Get involved', 'get-involved','','',NULL); ?></li>
	 		<li><?php printPageLinkURL('Paid support', 'paid-support','','',NULL); ?></li>
	 		<li><?php printPageLinkURL('Advertise', 'advertise','','',NULL); ?></li>
	 		<li><a href="https://github.com/zenphoto/zenphoto/issues?state=open" title="Bugtracker (GitHub)">Bugtracker <small>(GitHub)</small></a></li>
	 	</ul>
	 	<br />
	 	<h3>Legal stuff</h3>
		<ul class="downloadlinks">
			<li><?php printPageLinkURL('Licenses', 'licenses','','',NULL); ?></li>
			<li><a href="<?php echo WEBPATH; ?>/support/?terms-of-service=display">Forum rules & Terms of service</a></li>
			<li><?php printPageLinkURL('Privacy', 'privacy','','',NULL); ?></li>
			<li><?php printPageLinkURL('Contributor profile pages info', 'contributor-profile-page-information','','',NULL); ?></li>
		</ul>

	 </div>
</div>
<br style="clear:both" />
</div>
<?php
printAdminToolbox();
zp_apply_filter('theme_body_close');
?>
</body>
</html>