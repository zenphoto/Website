<?php 
	$confirmtext = '';
	if (!is_topic() ) : ?>
		<script type='text/javascript' src='bb-includes/js/jquery/jquery.js?ver=1.1.3.1'></script>
  <?php endif; ?>

<?php if ( !is_topic() ) : ?>
<p>Please read the <a href="http://www.zenphoto.org/support/topic.php?terms-of-service=display">forum rules and resources</a> before starting a new topic.</p>

<p>
	<label for="topic"><?php _e('Topic title: (be brief and descriptive)'); ?>
		<input name="topic" type="text" id="topic" size="50" maxlength="80" tabindex="1" />
	</label>
</p>
<?php endif; do_action( 'post_form_pre_post' ); ?>

<p>
	<label for="post_content"><?php _e('Post:'); ?>
		<textarea name="post_content" cols="50" rows="8" id="post_content" tabindex="3"></textarea>
	</label>
</p>
<?php if ( !is_topic() ) : ?>
<p>
	<label for="tags-input"><?php printf(__('Enter a few words (called <a href="%s">tags</a>) separated by commas to help someone find your topic:'), bb_get_tag_page_link()) ?>
		<input id="tags-input" name="tags" type="text" size="50" maxlength="100" value="<?php bb_tag_name(); ?> " tabindex="4" />
	</label>
</p>
<?php endif; ?>

<?php if ( is_bb_tag() || is_front() ) : ?>
<p>
	<label for="forum-id"><?php _e('Pick a section:'); ?>
		<?php bb_new_topic_forum_dropdown(); ?>
	</label>
</p>
<?php endif; ?>
<p class="submit">
  <input type="submit" id="postformsub" name="Submit" value="<?php echo attribute_escape( __('Send Post &raquo;') ); ?>" tabindex="4" />
</p>
<p><strong>Reporting possible bugs</strong><em>:<br>Please follow our <a href="http://www.zenphoto.org/news/general-contributor-guidelines#reporting-bugs">general bug report guidelines</a>. This helps us to help you.</em></p> 
<p><strong>3rd party solutions:</strong><em><br>We strongly recommend to put the name of the 3rd party solution you have a question about in the thread title so its author has a chance to notice it and give support. Many of them visit the forum quite regulary.The Zenphoto team can't support 3rd party solutions explicity.</em></p>
<p><?php _e('Allowed markup:'); ?> <code><?php allowed_markup(); ?></code>. <br /><?php _e('You can also put code in between backtick ( <code>`</code> ) characters.'); ?></p>