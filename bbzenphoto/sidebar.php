	<div id="sidebar">
			<div id="searchdiv">
			<?php include('search-form.php'); ?>
			</div>
			<hr />
			<div id="hottags">
				<h3><?php _e('Tags'); ?></h3>
				<p class="frontpageheatmap">
				<?php bb_tag_heat_map(); ?></p>
			</div>
			
			<iframe src="http://www.zenphoto.org/page/platinum-sponsors" width="280" height="300"></iframe>
			
			<div class="infobox paidsupport">
				<img src="<?php echo bb_active_theme_uri(); ?>images/icon-troubleshooting.png" alt="Troubleshooting" />
  			<h3>Problems? Lost?</h3>
  			<p>Visit the <a href="http://www.zenphoto.org/news/troubleshooting-guide">troubleshooting guide</a>.
  			</p>
  			<br clear="left" />
  		</div>
  		
			<div class="infobox paidsupport">
				<img src="<?php echo bb_active_theme_uri(); ?>images/icon-forum.png" alt="Paid support" />
  			<h3>Need project help?</h3>
  			<p>Visit the <a href="http://www.zenphoto.org/pages/paid-support">paid support page</a>.
  			</p>
  			<br clear="left" />
  		</div>
  		
			<?php if(function_exists('RSS_Display')) { ?>
				<div class="infobox">
					<h3>Latest news</h3>
				<?php echo RSS_Display("http://www.zenphoto.org/cache_html/rss/news_rss-news_withimages.xml", 5); ?>
				</div>
			<?php } ?>
			
			<div class="infobox">
  			<h3>Like using Zenphoto? Donate!</h3>
  			<p>
  			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="business" value="tharward@berkeley.edu">
					<input type="hidden" name="item_name" value="Zenphoto">
					<input type="hidden" name="no_note" value="1">
					<input type="hidden" name="currency_code" value="USD">
					<input type="hidden" name="tax" value="0">
					<input type="hidden" name="bn" value="PP-DonationsBF">
					<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
      	</form>
  			Your support helps pay for this server, and helps development of zenphoto. Thank you!</p>
  			<br />
      	<h3>Share!</h3>
      	<!-- AddThis Button BEGIN -->
				<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c0668c463045e32"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c0668c463045e32"></script>
				<!-- AddThis Button END -->
  		</div>
  		
  	  <div id="ads">
			<script type="text/javascript">
		<!--
			google_ad_client = "pub-7903690389990760";
			google_ad_width = 250;
			google_ad_height = 250;
			google_ad_format = "250x250_as";
			google_ad_type = "text";
			google_ad_channel ="";
			google_color_border = "CCCCCC";
			google_color_bg = "FFFFFF";
			google_color_link = "000000";
			google_color_url = "666666";
			google_color_text = "333333";
		//-->
		</script>
			<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
  </div>