<?php bb_get_header(); ?>

<h3 class="bbcrumb"><a href="<?php bb_option('uri'); ?>"><?php bb_option('name'); ?></a> &raquo; <?php _e('Register'); ?></h3>

<h2 id="register"><?php _e('Great!'); ?></h2>

<p><?php 
//$unusedtext = '<strong>Please note:</strong> If you are new forum member your <strong>first x posts and new topics are moderated and don not appear initially and might even dissapear.</strong>. This is sadly necessary because of spam preventing. It may take a few hours until we can moderate so please don not double post or your posts will be deleted.</p> Thanks for understanding.';
printf(__('Your registration as <strong>%s</strong> was successful. Within a few minutes you should receive an email with your password.'), $user_login) ?></p>

<?php bb_get_footer(); ?>
