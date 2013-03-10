		<div class="threadauthor">
			<?php post_author_avatar(); ?>
			<p>
				<?php
			// sbillard / acrylian / trisweb
			$user = bb_get_user(get_post_author_id());
			$userrights= $user->capabilities;
			if(is_array($userrights)) {
				if(array_key_exists("keymaster",$userrights)) { 
					echo '<img src="'.bb_get_active_theme_uri().'images/forumicon_developer.png" alt="Zenphoto development team" /><br />';
				}
				// kagutsuchi
				if(array_key_exists("moderator",$userrights) || array_key_exists("administrator",$userrights)) { 
					echo '<img src="'.bb_get_active_theme_uri().'images/forumicon_moderator.png" alt="Zenphoto support team" /><br />';
				}
				if(get_post_author_title($post_id) == 'Translator') {
					echo '<img src="'.bb_get_active_theme_uri().'images/forumicon_translator.png" alt="Zenphoto translation team" /><br />';
				}
			}
				?>
				<strong><?php post_author(); //post_author_link(); ?></strong><br />
				<small><?php post_author_title(); ?></small>
			</p>
		</div>
		
		<div class="threadpost">
			<div class="post"><?php post_text(); ?></div>
			<div class="poststuff"><?php printf( __('Posted %s ago'), bb_get_post_time() ); ?> <a href="<?php post_anchor_link(); ?>">#</a> <?php //post_ip_link(); ?> <?php post_edit_link(); ?> <?php post_delete_link(); ?> <?php user_delete_button(); ?></div>
		</div>
